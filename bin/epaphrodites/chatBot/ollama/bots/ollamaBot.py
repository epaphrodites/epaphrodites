import sys
import json
import logging
import requests
sys.path.append('bin/epaphrodites/chatBot/ollama/config/')

# Import custom module
try:
    from constants import _GENERATE_API_
    from decoration import Decorator
except ImportError as e:
    logging.error(f"Error importing InitJsonLoader module: {e}")
    sys.exit(1)

# Basic logging configuration
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

class OllamaClient:
    BASE_URL = _GENERATE_API_
    
    @staticmethod
    def _create_session():
        """Creates an HTTP session with retry handling."""
        session = requests.Session()
        return session
    
    @classmethod
    def generate_response(cls, prompt, model="llama3:8b", max_tokens=2000, 
                         temperature=0.6, top_p=0.9, stream=True, timeout_value=120,
                         output_at_end=False):
        """
        Sends a request to Ollama and returns the response.
        
        Args:
            prompt: Input text for the model
            model: Model name to use
            max_tokens: Maximum number of tokens to generate
            temperature: Controls creativity (0.0 to 1.0)
            top_p: Controls diversity (0.0 to 1.0)
            stream: Enable or disable streaming
            timeout_value: Timeout value in seconds
            output_at_end: Whether to output at end (deprecated)
        
        Returns:
            Dict with keys 'status', 'response' or 'error'
        """
        if not prompt or not isinstance(prompt, str):
            return {"status": "error", "error": "Invalid or empty prompt"}
            
        Decorator.timeout(timeout_value)
        def _do_request():
            try:
                # Parameter normalization
                max_tokens_safe = max(1, min(max_tokens, 4096))
                temperature_safe = max(0.0, min(temperature, 1.0))
                top_p_safe = max(0.0, min(top_p, 1.0))
                
                payload = {
                    "model": model,
                    "prompt": prompt,
                    "stream": stream,
                    "options": {
                        "num_predict": max_tokens_safe,
                        "temperature": temperature_safe,
                        "top_p": top_p_safe,
                        "num_ctx": 2048
                    }
                }
                
                headers = {"Content-Type": "application/json"}
                session = cls._create_session()
                
                if stream:
                    with session.post(cls.BASE_URL, json=payload, headers=headers, 
                                      stream=True, timeout=(5, timeout_value-5)) as response:
                        response.raise_for_status()
                        full_response = ""
                        
                        for line in response.iter_lines(decode_unicode=True):
                            if line:
                                try:
                                    data = json.loads(line.strip())
                                    chunk = data.get('response', '')
                                    
                                    if stream:
                                        print(chunk, end='', flush=True)
                                    
                                    full_response += chunk
                                    
                                    if data.get('done', False):
                                        break
                                except json.JSONDecodeError:
                                    continue
                        
                        print() 

                        return {"status": "success", "response": full_response, "printed_in_stream": True}
                else:
                    response = session.post(cls.BASE_URL, json=payload, headers=headers, 
                                           timeout=(5, timeout_value-5))
                    response.raise_for_status()
                    result = response.json()
                    # Return response for non-streaming request
                    return {"status": "success", "response": result.get('response', ''), "printed_in_stream": False}
                    
            except requests.exceptions.RequestException as e:
                return {"status": "error", "error": f"Network error: {str(e)}"}
            except json.JSONDecodeError as e:
                return {"status": "error", "error": f"JSON decoding error: {str(e)}"}
            except Exception as e:
                return {"status": "error", "error": f"Unexpected error: {str(e)}"}
        
        try:
            return _do_request()
        except TimeoutError as e:
            return {"status": "error", "error": f"Timeout: {str(e)}"}
        except Exception as e:
            return {"status": "error", "error": f"Unexpected error: {str(e)}"}
