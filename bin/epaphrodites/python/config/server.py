import os
import sys
import argparse
import json
from http.server import BaseHTTPRequestHandler, HTTPServer
sys.path.append(os.path.abspath(os.path.join(os.path.dirname(__file__), '../../../..')))
from bin.controllers.controllerMap.routes import Router

class CustomHandler(BaseHTTPRequestHandler):
    
    router = Router()

    def do_GET(self): self.handle_method("GET")
    def do_POST(self): self.handle_method("POST")
    def do_PUT(self): self.handle_method("PUT")
    def do_DELETE(self): self.handle_method("DELETE")

    def handle_method(self, method):
        handler, params = self.router.resolve(method, self.path)
        try:
            status_code, response = handler(self, *params)
        except Exception as e:
            status_code, response = 500, {"error": f"server error : {str(e)}"}

        self.send_response(status_code)
        self.send_header("Content-Type", "application/json")
        self.end_headers()
        self.wfile.write(json.dumps(response).encode("utf-8"))

class Server:
    
    def __init__(self, host='127.0.0.1', port=8000):
        self.host = host
        self.port = port
        self.httpd = HTTPServer((self.host, self.port), CustomHandler)

    def start(self):
       
        try:
            self.httpd.serve_forever()
        except KeyboardInterrupt:
         
            self.httpd.server_close()

def parse_arguments():
    parser = argparse.ArgumentParser(description="Background server")
    parser.add_argument("--host", default="127.0.0.1")
    parser.add_argument("--port", type=int, default=8000)
    return parser.parse_args()

if __name__ == "__main__":
    
    args = parse_arguments()
    
    server = Server(host=args.host, port=args.port)
    
    server.start()