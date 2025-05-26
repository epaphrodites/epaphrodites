import os
import sys
import json
import time
from http import HTTPStatus
from typing import Tuple, Union, Iterator
sys.path.append(os.path.abspath(os.path.join(os.path.dirname(__file__), '../../..')))
from bin.epaphrodites.chatBot.ragFaissModel.botCore import modelBotCore

class ApiControllers:
    
    def not_found(self, request, stream_handler=None, body=None):
        return 404, {"error": "Route not found"}
    
    def helloEpaphrodites(self, request, stream_handler=None, body=None):
        return 200, {"message": "Hello from python API", "streaming_supported": True}    
    
    def faissRagModel(self, request, stream_handler=None, body=None):
        try:
            data = {}
            if body:
                data = json.loads(body) if isinstance(body, str) else body
            
            prompt = data.get('prompt', 'prompt')
            user_id = data.get('user_id', 'user_id')
            stream = True  # Force le streaming
            
            bot = modelBotCore.get_instance(user_id)
        
            # Appel du modèle en mode streaming
            result = bot.ask(prompt, stream=stream)
            
            # Mode streaming
            response_chunks = []
            try:
                for chunk in result:
                    if "response" in chunk:
                        chunk_text = chunk["response"]
                        response_chunks.append(chunk_text)
                        
                        # Envoyer le chunk via stream_handler si disponible
                        if stream_handler:
                            chunk_data = {
                                "status": "streaming",
                                "chunk": chunk_text
                            }
                            # Utiliser la méthode correcte selon votre StreamingHandler
                            if hasattr(stream_handler, 'send_chunk'):
                                stream_handler.send_chunk(json.dumps(chunk_data))
                            elif hasattr(stream_handler, 'write'):
                                stream_handler.write(json.dumps(chunk_data) + '\n')
                            elif hasattr(stream_handler, 'send'):
                                stream_handler.send(json.dumps(chunk_data))
                            elif hasattr(stream_handler, 'emit'):
                                stream_handler.emit(json.dumps(chunk_data))
                            else:
                                # Debug: afficher les méthodes disponibles
                                print(f"Méthodes disponibles sur stream_handler: {dir(stream_handler)}")
                        
                        print(chunk_text, end='', flush=True)
                
                print()
                
                # Réponse finale pour le streaming
                final_response = {
                    "status": "success",
                    "prompt": ''.join(response_chunks),
                    "complete": True
                }
                
                if stream_handler:
                    final_data = json.dumps(final_response)
                    if hasattr(stream_handler, 'send_chunk'):
                        stream_handler.send_chunk(final_data)
                    elif hasattr(stream_handler, 'write'):
                        stream_handler.write(final_data + '\n')
                    elif hasattr(stream_handler, 'send'):
                        stream_handler.send(final_data)
                    elif hasattr(stream_handler, 'emit'):
                        stream_handler.emit(final_data)
                    
                    # Fermer le stream si la méthode existe
                    if hasattr(stream_handler, 'close'):
                        stream_handler.close()
                    elif hasattr(stream_handler, 'finish'):
                        stream_handler.finish()
                
                return 200, final_response
                
            except Exception as e:
                error_response = {
                    "status": "error",
                    "error": f"Erreur lors du streaming: {str(e)}"
                }
                
                if stream_handler:
                    error_data = json.dumps(error_response)
                    if hasattr(stream_handler, 'send_chunk'):
                        stream_handler.send_chunk(error_data)
                    elif hasattr(stream_handler, 'write'):
                        stream_handler.write(error_data + '\n')
                    elif hasattr(stream_handler, 'send'):
                        stream_handler.send(error_data)
                    elif hasattr(stream_handler, 'emit'):
                        stream_handler.emit(error_data)
                    
                    if hasattr(stream_handler, 'close'):
                        stream_handler.close()
                    elif hasattr(stream_handler, 'finish'):
                        stream_handler.finish()
                
                print(f"\n❌ Erreur lors du streaming: {e}")
                return 500, error_response
            
        except json.JSONDecodeError:
            return 400, {"error": "Invalid JSON format in request body"}
        except Exception as e:
            return 500, {"error": f"Error processing request: {str(e)}"}