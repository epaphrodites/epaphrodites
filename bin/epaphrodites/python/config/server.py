import os
import sys
import argparse
import json
import logging
import importlib.util
import threading
import time
from http.server import BaseHTTPRequestHandler, HTTPServer

# Configuration du logging pour la production
logging.basicConfig(
    level=logging.WARNING,
    format='%(asctime)s - %(levelname)s - %(message)s',
    stream=sys.stdout
)
logger = logging.getLogger(__name__)

def load_router():
    """Charge le router de manière plus robuste"""
    try:
        router_path = os.path.join(os.path.dirname(__file__), '../../../..', 'bin/controllers/controllerMap/routes.py')
        spec = importlib.util.spec_from_file_location("routes", router_path)
        routes_module = importlib.util.module_from_spec(spec)
        spec.loader.exec_module(routes_module)
        return routes_module.Router()
    except Exception as e:
        logger.error(f"Failed to load router: {e}")
        # Fallback: essayer avec sys.path
        sys.path.append(os.path.abspath(os.path.join(os.path.dirname(__file__), '../../../..')))
        from bin.controllers.controllerMap.routes import Router
        return Router()

class StreamingHandler:
    """Gestionnaire pour les réponses en streaming"""
    
    def __init__(self, request_handler):
        self.request_handler = request_handler
        self.is_streaming = False
        
    def start_stream(self, status_code=200, content_type="text/plain"):
        """Démarre un stream avec les headers appropriés"""
        self.request_handler.send_response(status_code)
        self.request_handler.send_header("Content-Type", content_type)
        self.request_handler.send_header("Transfer-Encoding", "chunked")
        self.request_handler.send_header("Cache-Control", "no-cache")
        self.request_handler.send_header("Connection", "keep-alive")
        self.request_handler.end_headers()
        self.is_streaming = True
        
    def write_chunk(self, data):
        """Écrit un chunk de données"""
        if not self.is_streaming:
            raise RuntimeError("Stream not started")
            
        if isinstance(data, str):
            data = data.encode('utf-8')
        elif isinstance(data, (dict, list)):
            data = json.dumps(data, separators=(',', ':')).encode('utf-8')
            
        # Format chunked: taille en hex + \r\n + données + \r\n
        chunk_size = hex(len(data))[2:].encode('utf-8')
        self.request_handler.wfile.write(chunk_size + b'\r\n')
        self.request_handler.wfile.write(data + b'\r\n')
        self.request_handler.wfile.flush()
        
    def write_sse_chunk(self, data, event_type="message", event_id=None):
        """Écrit un chunk au format Server-Sent Events"""
        if not self.is_streaming:
            raise RuntimeError("Stream not started")
            
        sse_data = ""
        if event_id:
            sse_data += f"id: {event_id}\n"
        if event_type:
            sse_data += f"event: {event_type}\n"
            
        if isinstance(data, (dict, list)):
            data = json.dumps(data, separators=(',', ':'))
            
        sse_data += f"data: {data}\n\n"
        
        chunk_data = sse_data.encode('utf-8')
        chunk_size = hex(len(chunk_data))[2:].encode('utf-8')
        self.request_handler.wfile.write(chunk_size + b'\r\n')
        self.request_handler.wfile.write(chunk_data + b'\r\n')
        self.request_handler.wfile.flush()
        
    def end_stream(self):
        """Termine le stream"""
        if self.is_streaming:
            # Chunk final de taille 0
            self.request_handler.wfile.write(b'0\r\n\r\n')
            self.request_handler.wfile.flush()
            self.is_streaming = False

class CustomHandler(BaseHTTPRequestHandler):
    
    router = load_router()
    
    # Limite de taille pour les requêtes (10MB)
    MAX_CONTENT_LENGTH = 10_000_000
    
    # Timeout pour les connexions de streaming (30 minutes)
    STREAM_TIMEOUT = 1800

    def do_GET(self): self.handle_method("GET")
    def do_POST(self): self.handle_method("POST")
    def do_PUT(self): self.handle_method("PUT")
    def do_DELETE(self): self.handle_method("DELETE")
    def do_PATCH(self): self.handle_method("PATCH")

    def handle_method(self, method):
        body = None
        
        # Lecture du body pour les méthodes qui en ont besoin
        if method in ["POST", "PUT", "PATCH"]:
            try:
                content_length = int(self.headers.get('Content-Length', 0))
                
                # Vérification de la taille
                if content_length > self.MAX_CONTENT_LENGTH:
                    self.send_error(413, "Request too large")
                    return
                    
                if content_length > 0:
                    body = self.rfile.read(content_length).decode('utf-8')
                    
            except UnicodeDecodeError:
                self.send_error(400, "Invalid encoding")
                return
            except ValueError:
                self.send_error(400, "Invalid Content-Length")
                return
            except Exception as e:
                logger.error(f"Error reading request body: {str(e)}")
                self.send_error(400, "Bad request")
                return

        # Création du gestionnaire de streaming
        stream_handler = StreamingHandler(self)
        
        # Résolution de la route et exécution du handler
        try:
            handler, params = self.router.resolve(method, self.path)
            
            # Passer le stream_handler au handler de route
            response = handler(self, stream_handler=stream_handler, body=body, *params)
            
            # Si la réponse n'est pas None et qu'on n'est pas en streaming, 
            # c'est une réponse classique
            if response is not None and not stream_handler.is_streaming:
                status_code, response_data = response
                self.send_json_response(status_code, response_data)
                
        except BrokenPipeError:
            # Client a fermé la connexion pendant le streaming
            logger.debug("Client disconnected during streaming")
            
        except Exception as e:
            logger.error(f"Handler error: {str(e)}")
            if not stream_handler.is_streaming:
                self.send_json_response(500, {"error": "Internal server error"})
        
        finally:
            # S'assurer que le stream est fermé
            if stream_handler.is_streaming:
                try:
                    stream_handler.end_stream()
                except:
                    pass

    def send_json_response(self, status_code, response):
        """Envoie une réponse JSON optimisée"""
        self.send_response(status_code)
        
        if isinstance(response, (dict, list)):
            self.send_header("Content-Type", "application/json")
            data = json.dumps(response, separators=(',', ':')).encode("utf-8")
        else:
            self.send_header("Content-Type", "text/plain")
            data = str(response).encode("utf-8")
        
        self.send_header("Content-Length", str(len(data)))
        self.send_header("Connection", "close")
        self.end_headers()
        self.wfile.write(data)

    def log_message(self, format, *args):
        """Supprime les logs automatiques de BaseHTTPRequestHandler"""
        pass

class ThreadedHTTPServer(HTTPServer):
    """Serveur HTTP avec support des threads pour le streaming"""
    
    def __init__(self, server_address, RequestHandlerClass, bind_and_activate=True):
        super().__init__(server_address, RequestHandlerClass, bind_and_activate)
        self.daemon_threads = True  # Les threads se ferment avec le processus principal

class Server:
    
    def __init__(self, host='127.0.0.1', port=8000):
        self.host = host
        self.port = port
        self.httpd = None

    def start(self):
        """Démarre le serveur avec gestion d'erreurs robuste"""
        try:
            self.httpd = ThreadedHTTPServer((self.host, self.port), CustomHandler)
            logger.info(f"Streaming server starting on {self.host}:{self.port}")
            self.httpd.serve_forever()
            
        except OSError as e:
            if e.errno == 98:  # Address already in use
                logger.error(f"Port {self.port} already in use")
            else:
                logger.error(f"Failed to start server: {e}")
            sys.exit(1)
            
        except KeyboardInterrupt:
            logger.info("Shutdown requested")
            
        except Exception as e:
            logger.error(f"Unexpected server error: {e}")
            
        finally:
            self.stop()

    def stop(self):
        """Arrêt propre du serveur"""
        if self.httpd:
            self.httpd.server_close()
            logger.info("Server stopped")

def parse_arguments():
    """Parse les arguments en ligne de commande"""
    parser = argparse.ArgumentParser(description="Production HTTP server with streaming support")
    parser.add_argument("--host", default="127.0.0.1", help="Host address")
    parser.add_argument("--port", type=int, default=8000, help="Port number")
    parser.add_argument("--debug", action="store_true", help="Enable debug logging")
    return parser.parse_args()

def main():
    """Point d'entrée principal"""
    args = parse_arguments()
    
    # Ajustement du niveau de logging si debug activé
    if args.debug:
        logging.getLogger().setLevel(logging.DEBUG)
        logger.debug("Debug mode enabled")
    
    # Création et démarrage du serveur
    server = Server(host=args.host, port=args.port)
    
    try:
        server.start()
    except KeyboardInterrupt:
        pass  # Géré dans server.start()

if __name__ == "__main__":
    main()

# Exemple d'utilisation dans un handler de route :
"""
def chat_stream_handler(request, stream_handler, body=None):
    # Pour du streaming de texte simple
    stream_handler.start_stream(content_type="text/plain")
    
    for i in range(10):
        stream_handler.write_chunk(f"Chunk {i}\n")
        time.sleep(0.1)  # Simulation d'un traitement
    
    stream_handler.end_stream()
    return None  # Pas de réponse classique

def chat_sse_handler(request, stream_handler, body=None):
    # Pour du Server-Sent Events (comme Ollama)
    stream_handler.start_stream(content_type="text/event-stream")
    
    for i in range(10):
        data = {"token": f"word_{i}", "done": i == 9}
        stream_handler.write_sse_chunk(data, event_type="token")
        time.sleep(0.1)
    
    stream_handler.end_stream()
    return None

def regular_handler(request, stream_handler, body=None):
    # Handler classique (non-streaming)
    return 200, {"message": "Hello World"}
"""