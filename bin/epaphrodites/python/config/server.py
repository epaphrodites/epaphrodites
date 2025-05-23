#!/usr/bin/env python3
"""
Python HTTP Server for API routing with enhanced features
Supports GET/POST requests, streaming responses, and concurrent handling
"""

import sys
import os
import json
import http.server
import socketserver
import argparse
import logging
import signal
import threading
import time
from urllib.parse import urlparse, parse_qs
from pathlib import Path
from typing import Tuple, Union, Iterator, Dict, Any, Optional
import socket
from contextlib import contextmanager

class ServerConfig:
    """Configuration constants for the server"""
    DEFAULT_HOST = "127.0.0.1"
    DEFAULT_PORT = 5001
    LOG_FILE = "pythonServer.log"
    MAX_CONTENT_LENGTH = 10 * 1024 * 1024  # 10MB
    REQUEST_TIMEOUT = 30  # seconds
    CHUNK_SIZE = 8192
    
    # HTTP Headers
    JSON_CONTENT_TYPE = "application/json"
    SSE_CONTENT_TYPE = "text/event-stream"
    SECURITY_HEADERS = {
        "X-Content-Type-Options": "nosniff",
        "X-Frame-Options": "DENY",
        "X-XSS-Protection": "1; mode=block"
    }


class ServerLogger:
    """Enhanced logging configuration"""
    
    @staticmethod
    def setup_logger(log_file: str = ServerConfig.LOG_FILE) -> logging.Logger:
        """Setup and configure logger with file and console output"""
        logger = logging.getLogger(__name__)
        logger.setLevel(logging.INFO)
        
        # Remove existing handlers to avoid duplicates
        logger.handlers.clear()
        
        # File handler
        file_handler = logging.FileHandler(log_file)
        file_handler.setLevel(logging.INFO)
        
        # Console handler
        console_handler = logging.StreamHandler()
        console_handler.setLevel(logging.INFO)
        
        # Formatter
        formatter = logging.Formatter(
            '%(asctime)s [%(levelname)s] %(name)s: %(message)s',
            datefmt='%Y-%m-%d %H:%M:%S'
        )
        
        file_handler.setFormatter(formatter)
        console_handler.setFormatter(formatter)
        
        logger.addHandler(file_handler)
        logger.addHandler(console_handler)
        
        return logger


class RouteImporter:
    """Handles dynamic import of routes with better error handling"""
    
    @staticmethod
    def import_routes():
        """Import Routes class with enhanced error handling"""
        try:
            # Add root path for imports
            root_path = Path(__file__).parent.parent.parent.parent.absolute()
            if str(root_path) not in sys.path:
                sys.path.insert(0, str(root_path))
            
            from bin.controllers.controllerMap.routes import Routes
            return Routes
        except ImportError as e:
            raise ImportError(f"Failed to import Routes module: {e}")
        except Exception as e:
            raise Exception(f"Unexpected error importing Routes: {e}")


class ResponseNormalizer:
    """Handles response normalization and validation"""
    
    @staticmethod
    def normalize_response(result: Union[tuple, str, dict, list, Iterator]) -> Tuple[Union[dict, list, Iterator], int]:
        """
        Normalize response to standard format (response, status_code)
        
        Args:
            result: Raw response from route handler
            
        Returns:
            Tuple of (normalized_response, http_status_code)
        """
        if isinstance(result, tuple) and len(result) == 2:
            response, status = result
            return ResponseNormalizer._process_response_content(response), status
        
        if isinstance(result, str):
            return ResponseNormalizer._process_string_response(result)
        
        if isinstance(result, (dict, list)):
            return result, 200
        
        if hasattr(result, '__iter__') and not isinstance(result, (str, bytes)):
            return result, 200
        
        return {"error": "Invalid response format", "type": type(result).__name__}, 500
    
    @staticmethod
    def _process_response_content(response: Any) -> Union[dict, list, Iterator]:
        """Process response content and handle JSON parsing"""
        if isinstance(response, str):
            try:
                return json.loads(response)
            except json.JSONDecodeError:
                return {"error": "Invalid JSON response", "raw_content": response}
        return response
    
    @staticmethod
    def _process_string_response(result: str) -> Tuple[dict, int]:
        """Process string responses and attempt JSON parsing"""
        try:
            return json.loads(result), 200
        except json.JSONDecodeError:
            return {"message": result}, 200


class SecurityValidator:
    """Security validation utilities"""
    
    @staticmethod
    def validate_path(path: str) -> bool:
        """Validate request path for security issues"""
        if not path or not path.startswith("/"):
            return False
        
        # Check for directory traversal attempts
        if ".." in path or "~" in path:
            return False
        
        # Check for null bytes
        if "\x00" in path:
            return False
        
        return True
    
    @staticmethod
    def validate_content_length(content_length: int) -> bool:
        """Validate content length against maximum allowed"""
        return 0 <= content_length <= ServerConfig.MAX_CONTENT_LENGTH


class CustomHTTPRequestHandler(http.server.BaseHTTPRequestHandler):
    """Enhanced HTTP request handler with security and performance improvements"""
    
    def __init__(self, *args, **kwargs):
        self.logger = ServerLogger.setup_logger()
        self.routes = None
        super().__init__(*args, **kwargs)
    
    def setup(self):
        """Setup handler with Routes instance"""
        if not self.routes:
            try:
                routes_class = RouteImporter.import_routes()
                self.routes = routes_class()
            except Exception as e:
                self.logger.error(f"Failed to setup routes: {e}")
                self.routes = None
    
    def do_GET(self) -> None:
        """Handle GET requests with enhanced security and error handling"""
        start_time = time.time()
        parsed_path = urlparse(self.path)
        path = parsed_path.path
        query_params = parse_qs(parsed_path.query)
        
        try:
            # Security validation
            if not SecurityValidator.validate_path(path):
                self._send_error_response(400, {"error": "Invalid or unsafe path"})
                return
            
            # Setup routes if needed
            self.setup()
            if not self.routes:
                self._send_error_response(503, {"error": "Service temporarily unavailable"})
                return
            
            # Route the request
            result = self.routes.routes(path, query_params)
            response, status = ResponseNormalizer.normalize_response(result)
            
            # Send response
            self._send_json_response(response, status)
            
            # Log request
            duration = time.time() - start_time
            self.logger.info(f"GET {path} - Status: {status} - Duration: {duration:.3f}s")
            
        except Exception as e:
            self.logger.error(f"Error handling GET {path}: {e}")
            self._send_error_response(500, {
                "error": "Internal Server Error",
                "message": "An unexpected error occurred"
            })
    
    def do_POST(self) -> None:
        """Handle POST requests with streaming support and security validation"""
        start_time = time.time()
        parsed_path = urlparse(self.path)
        path = parsed_path.path
        
        try:
            # Security validation
            if not SecurityValidator.validate_path(path):
                self._send_error_response(400, {"error": "Invalid or unsafe path"})
                return
            
            # Validate content length
            content_length = int(self.headers.get('Content-Length', 0))
            if not SecurityValidator.validate_content_length(content_length):
                self._send_error_response(413, {"error": "Request entity too large"})
                return
            
            # Read and parse request data
            request_data = self._read_request_data(content_length)
            
            # Setup routes if needed
            self.setup()
            if not self.routes:
                self._send_error_response(503, {"error": "Service temporarily unavailable"})
                return
            
            # Route the request
            result = self.routes.routes(path, request_data)
            response, status = ResponseNormalizer.normalize_response(result)
            
            # Handle streaming vs regular response
            if self._is_streaming_request() and hasattr(response, '__iter__') and not isinstance(response, (str, bytes, dict, list)):
                self._send_streaming_response(response, status)
            else:
                self._send_json_response(response, status)
            
            # Log request
            duration = time.time() - start_time
            self.logger.info(f"POST {path} - Status: {status} - Duration: {duration:.3f}s")
            
        except json.JSONDecodeError:
            self.logger.error(f"Invalid JSON in POST {path}")
            self._send_error_response(400, {"error": "Invalid JSON payload"})
        except Exception as e:
            self.logger.error(f"Error handling POST {path}: {e}")
            self._send_error_response(500, {
                "error": "Internal Server Error", 
                "message": "An unexpected error occurred"
            })
    
    def _read_request_data(self, content_length: int) -> Dict[str, Any]:
        """Read and parse request data with timeout handling"""
        if content_length == 0:
            return {}
        
        try:
            # Set timeout for reading
            self.connection.settimeout(ServerConfig.REQUEST_TIMEOUT)
            data = self.rfile.read(content_length).decode('utf-8')
            return json.loads(data)
        except socket.timeout:
            raise Exception("Request timeout while reading data")
        except UnicodeDecodeError:
            raise Exception("Invalid UTF-8 encoding in request data")
        finally:
            # Reset timeout
            self.connection.settimeout(None)
    
    def _is_streaming_request(self) -> bool:
        """Check if client expects streaming response"""
        accept_header = self.headers.get('Accept', '')
        return 'text/event-stream' in accept_header or 'application/stream+json' in accept_header
    
    def _send_json_response(self, response: Union[dict, list, Iterator], status: int) -> None:
        """Send JSON response with security headers"""
        self.send_response(status)
        self.send_header("Content-Type", ServerConfig.JSON_CONTENT_TYPE)
        self._send_security_headers()
        self.end_headers()
        
        if isinstance(response, (dict, list)):
            content = json.dumps(response, ensure_ascii=False)
            self.wfile.write(content.encode('utf-8'))
        else:
            # Handle other types
            self.wfile.write(str(response).encode('utf-8'))
    
    def _send_streaming_response(self, response: Iterator, status: int) -> None:
        """Send streaming response for Server-Sent Events"""
        self.send_response(status)
        self.send_header("Content-Type", ServerConfig.SSE_CONTENT_TYPE)
        self.send_header("Cache-Control", "no-cache")
        self.send_header("Connection", "keep-alive")
        self._send_security_headers()
        self.end_headers()
        
        try:
            for chunk in response:
                if isinstance(chunk, dict):
                    chunk = json.dumps(chunk)
                elif not isinstance(chunk, str):
                    chunk = str(chunk)
                
                self.wfile.write(f"data: {chunk}\n\n".encode('utf-8'))
                self.wfile.flush()
        except Exception as e:
            self.logger.error(f"Error in streaming response: {e}")
    
    def _send_error_response(self, code: int, body: Dict[str, Any]) -> None:
        """Send error response with consistent format"""
        self.send_response(code)
        self.send_header("Content-Type", ServerConfig.JSON_CONTENT_TYPE)
        self._send_security_headers()
        self.end_headers()
        
        error_response = {
            "error": True,
            "status": code,
            **body,
            "timestamp": time.time()
        }
        
        self.wfile.write(json.dumps(error_response).encode('utf-8'))
    
    def _send_security_headers(self) -> None:
        """Send security headers"""
        for header, value in ServerConfig.SECURITY_HEADERS.items():
            self.send_header(header, value)
    
    def log_message(self, format: str, *args) -> None:
        """Override default logging - handled by our custom logger"""
        pass


class ThreadedHTTPServer(socketserver.ThreadingMixIn, http.server.HTTPServer):
    """Enhanced threaded HTTP server with graceful shutdown"""
    daemon_threads = True
    allow_reuse_address = True
    
    def __init__(self, *args, **kwargs):
        super().__init__(*args, **kwargs)
        self.logger = ServerLogger.setup_logger()
        self._shutdown_event = threading.Event()
    
    def server_close(self):
        """Enhanced server cleanup"""
        self.logger.info("Shutting down server...")
        self._shutdown_event.set()
        super().server_close()


class ServerManager:
    """Manages server lifecycle and utilities"""
    
    def __init__(self):
        self.logger = ServerLogger.setup_logger()
        self.server = None
    
    @staticmethod
    def is_port_available(host: str, port: int) -> bool:
        """Check if the specified port is available"""
        try:
            with socket.socket(socket.AF_INET, socket.SOCK_STREAM) as s:
                s.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)
                s.bind((host, port))
                return True
        except socket.error:
            return False
    
    def setup_signal_handlers(self) -> None:
        """Setup signal handlers for graceful shutdown"""
        def signal_handler(signum, frame):
            self.logger.info(f"Received signal {signum}, shutting down...")
            if self.server:
                self.server.shutdown()
            sys.exit(0)
        
        signal.signal(signal.SIGINT, signal_handler)
        signal.signal(signal.SIGTERM, signal_handler)
    
    def run_server(self, host: str, port: int) -> None:
        """Run the HTTP server with enhanced error handling and monitoring"""
        try:
            # Validate port range
            if not (1 <= port <= 65535):
                raise ValueError(f"Invalid port number: {port}. Must be between 1 and 65535")
            
            # Check port availability
            if not self.is_port_available(host, port):
                raise RuntimeError(f"Port {port} is already in use on {host}")
            
            # Setup signal handlers
            self.setup_signal_handlers()
            
            # Create and start server
            self.server = ThreadedHTTPServer((host, port), CustomHTTPRequestHandler)
            
            self.logger.info(f"Server started successfully at http://{host}:{port}")
            print(f"✅ Server running at http://{host}:{port}")
            print("Press Ctrl+C to stop the server")
            
            # Start serving requests
            self.server.serve_forever()
            
        except KeyboardInterrupt:
            self.logger.info("Server stopped by user (Ctrl+C)")
            print("\n🛑 Stopping server...")
        except Exception as e:
            self.logger.error(f"Failed to start server: {e}")
            print(f"❌ Server failed to start: {e}")
            sys.exit(1)
        finally:
            if self.server:
                self.server.server_close()
            self.logger.info("Server shutdown complete")


def create_argument_parser() -> argparse.ArgumentParser:
    """Create and configure argument parser"""
    parser = argparse.ArgumentParser(
        description="Launch Python HTTP API server with enhanced features",
        formatter_class=argparse.RawDescriptionHelpFormatter,
        epilog="""
Examples:
  python server.py --port 8080
  python server.py --port 5000 --host 0.0.0.0
        """
    )
    
    parser.add_argument(
        '--port', 
        type=int, 
        default=ServerConfig.DEFAULT_PORT,
        help=f'Port to listen on (default: {ServerConfig.DEFAULT_PORT})'
    )
    
    parser.add_argument(
        '--host',
        type=str,
        default=ServerConfig.DEFAULT_HOST,
        help=f'Host to bind to (default: {ServerConfig.DEFAULT_HOST})'
    )
    
    parser.add_argument(
        '--log-level',
        choices=['DEBUG', 'INFO', 'WARNING', 'ERROR'],
        default='INFO',
        help='Set logging level (default: INFO)'
    )
    
    return parser


def main():
    """Main entry point"""
    parser = create_argument_parser()
    args = parser.parse_args()
    
    # Setup logging level
    logging.getLogger().setLevel(getattr(logging, args.log_level))
    
    # Create and run server
    server_manager = ServerManager()
    server_manager.run_server(args.host, args.port)


if __name__ == '__main__':
    main()