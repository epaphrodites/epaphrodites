from http.server import BaseHTTPRequestHandler, HTTPServer
import json
import socket
import signal
import logging
import os
import sys

# Add the current directory to the path to ensure we can import api
sys.path.append(os.path.dirname(os.path.abspath(__file__)))
from api import handle_request

# Configure logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s'
)
logger = logging.getLogger("server")

class APIServer(BaseHTTPRequestHandler):
    """HTTP request handler for the API server."""
    
    # Class-wide setting for controlling CORS
    enable_cors = True
    
    def _set_headers(self, status_code, content_type='application/json'):
        """Set response headers."""
        self.send_response(status_code)
        self.send_header('Content-Type', content_type)
        
        # Add CORS headers if enabled
        if self.enable_cors:
            self.send_header('Access-Control-Allow-Origin', '*')
            self.send_header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')
            self.send_header('Access-Control-Allow-Headers', 'Content-Type')
        
        self.end_headers()
    
    def _send_json_response(self, status_code, data):
        """Send a JSON response with the given status code and data."""
        self._set_headers(status_code)
        self.wfile.write(json.dumps(data, indent=2).encode('utf-8'))
    
    def do_OPTIONS(self):
        """Handle OPTIONS requests for CORS preflight."""
        self._set_headers(200)
    
    def do_GET(self):
        """Handle GET requests."""
        try:
            status_code, response_data = handle_request(self.path)
            self._send_json_response(status_code, response_data)
        except Exception as e:
            logger.error(f"Unhandled exception: {str(e)}")
            self._send_json_response(500, {"error": "Internal server error"})
    
    def log_message(self, format, *args):
        """Override log_message to use our logger instead of printing to stderr."""
        logger.info(f"{self.address_string()} - {format % args}")


def find_available_port(start_port=5000, max_attempts=10):
    """Find an available port starting from start_port."""
    for port in range(start_port, start_port + max_attempts):
        with socket.socket(socket.AF_INET, socket.SOCK_STREAM) as s:
            try:
                s.bind(('localhost', port))
                return port
            except socket.error:
                continue
    raise RuntimeError(f"Could not find an available port after {max_attempts} attempts")


def run_server(host='localhost', port=5000):
    """Start the HTTP server and handle keyboard interrupts gracefully."""
    try:
        # Try to use the specified port, or find an available one
        try:
            server = HTTPServer((host, port), APIServer)
            actual_port = port
        except socket.error:
            logger.warning(f"Port {port} is in use. Finding an available port...")
            actual_port = find_available_port(port)
            server = HTTPServer((host, actual_port), APIServer)
        
        server_address = f"http://{host}:{actual_port}"
        logger.info(f"Starting server on {server_address}")
        logger.info(f"API documentation available at {server_address}/api/links")
        
        # Handle graceful shutdown on SIGINT (Ctrl+C)
        def shutdown_handler(signum, frame):
            logger.info("Shutting down server...")
            server.shutdown()
            sys.exit(0)
        
        signal.signal(signal.SIGINT, shutdown_handler)
        
        # Start the server
        server.serve_forever()
        
    except Exception as e:
        logger.error(f"Server error: {str(e)}")
        sys.exit(1)


if __name__ == "__main__":
    
    port = int(sys.argv[1]) if len(sys.argv) > 1 else 5000
    host = sys.argv[2] if len(sys.argv) > 2 else "127.0.0.1"
    print(f"✅ Serveur Python démarré sur http://{host}:{port}")

    run_server(host, port)