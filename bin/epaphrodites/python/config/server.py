import sys
import os
import json
import http.server
import socketserver
import argparse
import logging
from urllib.parse import urlparse
import socket
from typing import Tuple, Union, Iterator

# Configure logging
logging.basicConfig(
    filename='pythonServer.log',
    level=logging.INFO,
    format='%(asctime)s [%(levelname)s] %(message)s',
    datefmt='%Y-%m-%d %H:%M:%S'
)
logger = logging.getLogger(__name__)

# Ajouter le chemin racine pour les imports
sys.path.append(os.path.abspath(os.path.join(os.path.dirname(__file__), '../../../..')))
try:
    from bin.controllers.controllerMap.routes import Routes
except ImportError as e:
    logger.error(f"Failed to import Routes: {e}")
    sys.exit(1)

HOST = "127.0.0.1"

class ThreadedHTTPServer(socketserver.ThreadingMixIn, http.server.HTTPServer):
    """Threaded HTTP server to handle multiple requests concurrently"""
    daemon_threads = True

class CustomHandler(http.server.BaseHTTPRequestHandler):
    """Custom HTTP request handler for GET and POST requests"""

    def do_GET(self) -> None:
        """Handle GET requests and route them to the appropriate handler"""
        parsed_path = urlparse(self.path)
        path = parsed_path.path

        try:
            # Validate path to prevent directory traversal or invalid routes
            if ".." in path or not path.startswith("/"):
                self.send_error_response(400, {"error": "Invalid path"})
                return

            result = Routes.routes(path)
            response, status = self._normalize_response(result)

            self.send_response(status)
            self.send_header("Content-Type", "application/json")
            self.send_header("X-Content-Type-Options", "nosniff")
            self.end_headers()

            if isinstance(response, (dict, list)):
                self.wfile.write(json.dumps(response).encode('utf-8'))
            elif callable(getattr(response, '__iter__', None)):
                for chunk in response:
                    self.wfile.write(chunk.encode('utf-8'))
                    self.wfile.flush()
            else:
                self.wfile.write(str(response).encode('utf-8'))

            logger.info(f"GET {path} - Status: {status}")
        except Exception as e:
            logger.error(f"Error handling GET {path}: {e}")
            self.send_error_response(500, {"error": "Internal Server Error", "details": str(e)})

    def do_POST(self) -> None:
        """Handle POST requests, including streaming responses"""
        parsed_path = urlparse(self.path)
        path = parsed_path.path

        try:
            # Validate path
            if ".." in path or not path.startswith("/"):
                self.send_error_response(400, {"error": "Invalid path"})
                return

            # Read request body
            content_length = int(self.headers.get('Content-Length', 0))
            data = self.rfile.read(content_length).decode('utf-8')
            request_data = json.loads(data) if content_length > 0 else {}

            # Route request
            result = Routes.routes(path, request_data)
            response, status = self._normalize_response(result)

            self.send_response(status)
            if callable(getattr(response, '__iter__', None)) and self.headers.get('Accept') == 'text/event-stream':
                self.send_header("Content-Type", "text/event-stream")
                self.send_header("Cache-Control", "no-cache")
                self.send_header("Connection", "keep-alive")
            else:
                self.send_header("Content-Type", "application/json")
                self.send_header("X-Content-Type-Options", "nosniff")

            self.end_headers()

            if callable(getattr(response, '__iter__', None)):
                for chunk in response:
                    self.wfile.write(chunk.encode('utf-8'))
                    self.wfile.flush()
            else:
                self.wfile.write(json.dumps(response).encode('utf-8'))

            logger.info(f"POST {path} - Status: {status}")
        except json.JSONDecodeError:
            logger.error(f"Invalid JSON in POST {path}")
            self.send_error_response(400, {"error": "Invalid JSON payload"})
        except Exception as e:
            logger.error(f"Error handling POST {path}: {e}")
            self.send_error_response(500, {"error": "Internal Server Error", "details": str(e)})

    def _normalize_response(self, result: Union[tuple, str, dict, Iterator]) -> Tuple[Union[dict, Iterator], int]:
        """Normalize the response format to (response, status)"""
        if isinstance(result, tuple):
            response, status = result
            if isinstance(response, str):
                try:
                    response = json.loads(response)
                except json.JSONDecodeError:
                    response = {"error": "Invalid JSON response", "details": response}
                    status = 500
            return response, status
        elif isinstance(result, str):
            try:
                response = json.loads(result)
                return response, 200
            except json.JSONDecodeError:
                return {"error": "Invalid JSON response", "details": result}, 500
        elif isinstance(result, (dict, list, Iterator)):
            return result, 200
        else:
            return {"error": "Invalid response format"}, 500

    def send_error_response(self, code: int, body: dict) -> None:
        """Send an error response with JSON body"""
        self.send_response(code)
        self.send_header("Content-Type", "application/json")
        self.send_header("X-Content-Type-Options", "nosniff")
        self.end_headers()
        self.wfile.write(json.dumps(body).encode('utf-8'))

    def log_message(self, format: str, *args) -> None:
        """Override default logging to use custom logger"""
        pass

def is_port_free(host: str, port: int) -> bool:
    """Check if the specified port is free"""
    with socket.socket(socket.AF_INET, socket.SOCK_STREAM) as s:
        try:
            s.bind((host, port))
            return True
        except socket.error:
            return False

def run_server(host: str, port: int) -> None:
    """Run the HTTP server on the specified host and port"""
    try:
        # Check if port is free
        if not is_port_free(host, port):
            logger.error(f"Port {port} is already in use")
            sys.exit(1)

        server = ThreadedHTTPServer((host, port), CustomHandler)
        logger.info(f"Server started at http://{host}:{port}")
        print(f"✅ Server running at http://{host}:{port}")
        server.serve_forever()
    except KeyboardInterrupt:
        logger.info("Server stopped by user")
        print("\n🛑 Stopping server...")
        server.shutdown()
    except Exception as e:
        logger.error(f"Failed to start server: {e}")
        sys.exit(1)

if __name__ == '__main__':
    parser = argparse.ArgumentParser(description="Launch Python API server")
    parser.add_argument('--port', type=int, default=5001, help='Port to listen on')
    args = parser.parse_args()

    if not (1 <= args.port <= 65535):
        logger.error(f"Invalid port number: {args.port}. Must be between 1 and 65535")
        sys.exit(1)

    run_server(HOST, args.port)