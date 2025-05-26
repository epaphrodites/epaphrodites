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
            
            bot = modelBotCore.get_instance(user_id)
        
            result = bot.ask(prompt, stream=False)
            
            response = {
                "status": "success",
                "model": "faiss-rag",
                "prompt": result
            }
            return 200, response
            
        except json.JSONDecodeError:
            return 400, {"error": "Invalid JSON format in request body"}
        except Exception as e:
            return 500, {"error": f"Error processing request: {str(e)}"}