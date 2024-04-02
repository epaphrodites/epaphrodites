import json
import re

class InitJsonLoader:
    
    @staticmethod
    def add_quotes(string):
        result = []
        elements = re.findall(r'([^:]+):(".*?"|[^,]+)', string.strip('{}'))
        for i, element in enumerate(elements):
            key, value = element
            if not key.startswith('"') and not key.endswith('"'):
                key = '"' + key.strip() + '"'

            if value.startswith('"') and value.endswith('"'):
                pass  # La valeur est déjà entourée de guillemets doubles
            elif value in ['true', 'false', 'null']:
                pass  # La valeur est une constante JSON, ne pas l'entourer de guillemets
            else:
                value = '"' + value.strip() + '"'

            if i == 0:
                result.append(key + ':' + value)
            else:
                previous_value_quoted = result[-1].endswith('"')
                current_value_quoted = value.startswith('"') and value.endswith('"')
                if previous_value_quoted or current_value_quoted:
                    result.append(',' + key + ':' + value)
                else:
                    result.append(key + ':' + value)

        return '{' + ''.join(result) + '}'

    @staticmethod
    def loadJsonValues(json_values):

        json_values = InitJsonLoader.add_quotes(json_values)
       # values = json.loads(json_values)
        return json_values