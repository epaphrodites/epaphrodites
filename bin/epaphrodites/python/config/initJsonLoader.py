import json
import re

class InitJsonLoader:
    
    @staticmethod
    def add_quotes(string):
        result = []
        elements = re.findall(r'([^:]+):(".*?"|[^,]+)', string.strip('{}'))
        for element in elements:
            key, value = element
            if not key.startswith('"') and not key.endswith('"'):
                key = '"' + key.strip() + '"'

            if value.startswith('"') and value.endswith('"'):
                pass  
            elif value in ['true', 'false', 'null']:
                pass
            else:
                value = '"' + value.strip() + '"'
            result.append(key + ':' + value)
        return '{' + ''.join(result) + '}'

    @staticmethod
    def loadJsonValues(json_values):

        json_values = InitJsonLoader.add_quotes(json_values)
       # values = json.loads(json_values)
        return json_values