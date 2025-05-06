import sys
import json
import requests
sys.path.append('bin/epaphrodites/python/config/')
sys.path.append('bin/epaphrodites/chatBot/ollama/bots/')
sys.path.append('bin/epaphrodites/chatBot/ollama/config/')
from ollamaBotCore import OllamaBotCore
from ollamaBot import OllamaClient
from constants import _TAG_API_
from initJsonLoader import InitJsonLoader

class lunchOllamaBot:

    @staticmethod
    def func_lunch_ollama(params):
       result = OllamaBotCore.collectOllamaLlmData(params)
       return result

if __name__ == '__main__':  
    
 # Configure stdout to avoid buffering
    if hasattr(sys.stdout, 'reconfigure'):
        sys.stdout.reconfigure(line_buffering=True)
    
    if len(sys.argv) < 2:
        print(json.dumps({"status": "error", "error": "Usage: python ollama.py '<json_values>'"}))
        sys.exit(1)
    
    try:
        # Decode JSON arguments with custom loader
        json_values_encoded = sys.argv[1]
        json_datas = InitJsonLoader.loadJsonValues(json_values_encoded, ',')
        
        # Check that prompt is present
        if 'prompt' not in json_datas:
            print(json.dumps({"status": "error", "error": "The 'prompt' field is required"}))
            sys.exit(1)
        
        # Parameters with default values
        params = {
            'model': json_datas.get('model', 'llama3:8b'),
            'max_tokens': json_datas.get('max_tokens', 200),
            'temperature': json_datas.get('temperature', 0.7),
            'top_p': json_datas.get('top_p', 0.9),
            'stream': json_datas.get('stream', True),
            'timeout_value': json_datas.get('timeout', 60)
        }
        
        # Check if Ollama is available
        try:
            requests.get(_TAG_API_, timeout=3)
        except requests.exceptions.RequestException:
            print(json.dumps({"status": "error", "error": "Ollama server not available"}))
            sys.exit(1)
        
        # Call Ollama
        result = OllamaClient.generate_response(
            prompt=json_datas['prompt'],
            **params
        )
        
        # Output formatting
        if result['status'] == 'success':
            # For success, only display response if necessary
            # Don't reprint if already printed in streaming mode
            if not result.get('printed_in_stream', False):
                print(result['response'])
        else:
            # For errors, display error message formatted as JSON
            print(json.dumps(result, ensure_ascii=False))
            sys.exit(1)
            
    except ValueError as e:
        print(json.dumps({"status": "error", "error": f"JSON parsing error: {str(e)}"}))
        sys.exit(1)
    except Exception as e:
        print(json.dumps({"status": "error", "error": f"Unexpected error: {str(e)}"}))
        sys.exit(1)
            
       