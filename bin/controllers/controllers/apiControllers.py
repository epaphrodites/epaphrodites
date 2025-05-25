import os
import sys
import json
import time
from http import HTTPStatus
from typing import Tuple, Union, Iterator

class ApiControllers:
    
    def not_found(self, request, stream_handler=None, body=None):

        return 404, {"error": "Route not found"}
    
    def helloEpaphrodites(self, request, stream_handler=None, body=None):

        return 200, {"message": "Hello from python API", "streaming_supported": True}    
    
    def faissRagModel(self, request, stream_handler=None, body=None):
        """Handler RAG classique (sans streaming)"""
        try:
            data = {}
            if body:
                data = json.loads(body) if isinstance(body, str) else body
            
            prompt = data.get('prompt', 'prompt')
            
            response = {
                "status": "success",
                "model": "faiss-rag",
                "prompt": prompt,
                "response": f"Réponse RAG pour: {prompt}"
            }
            return 200, response
            
        except json.JSONDecodeError:
            return 400, {"error": "Invalid JSON format in request body"}
        except Exception as e:
            return 500, {"error": f"Error processing request: {str(e)}"}