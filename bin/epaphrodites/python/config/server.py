import sys
import os
import json
import http.server
import socketserver
import argparse
from urllib.parse import urlparse

# Ajouter le chemin racine pour les imports
sys.path.append(os.path.abspath(os.path.join(os.path.dirname(__file__), '../../../..')))
from bin.controllers.controllerMap.routes import Routes

HOST = "127.0.0.1"

class ThreadedHTTPServer(socketserver.ThreadingMixIn, http.server.HTTPServer):
    
    daemon_threads = True

class CustomHandler(http.server.BaseHTTPRequestHandler):

    def do_GET(self):
        parsed_path = urlparse(self.path)
        path = parsed_path.path

        try:
            response, status = Routes.routes(path)
            self.send_response(status)
            self.send_header("Content-Type", "application/json")
            self.end_headers()
            self.wfile.write(response.encode('utf-8'))
        except Exception as e:
            self.send_error_response(500, {"error": "Internal Server Error", "details": str(e)})

    def send_error_response(self, code, body):
        self.send_response(code)
        self.send_header("Content-Type", "application/json")
        self.end_headers()
        self.wfile.write(json.dumps(body).encode('utf-8'))

    def log_message(self, format, *args):
        # Uncomment to enable request logs
        # super().log_message(format, *args)
        pass

def run_server(host: str, port: int):
    server = ThreadedHTTPServer((host, port), CustomHandler)
    print(f"✅ Server running at http://{host}:{port}")
    try:
        server.serve_forever()
    except KeyboardInterrupt:
        print("\n🛑 Stopping server...")
        server.shutdown()

if __name__ == '__main__':
    
    parser = argparse.ArgumentParser(description="Launch Python API server")
    
    parser.add_argument('--port', type=int, default=5000, help='Port to listen on')
    
    args = parser.parse_args()

    run_server(HOST, args.port)
