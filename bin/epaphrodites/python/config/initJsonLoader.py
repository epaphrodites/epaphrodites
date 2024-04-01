import json

class InitJsonLoader:
    
    @staticmethod
    def add_quotes(string):
        result = []
        elements = string.strip('{}').split(',')
        for element in elements:
            key, value = element.split(':')
            if not key.startswith('"'): key = '"' + key
            if not key.endswith('"'): key = key + '"'
            if not value.startswith('"') and value not in ['true', 'false', 'null']: value = '"' + value
            if not value.endswith('"') and value not in ['true', 'false', 'null']: value = value + '"'
            result.append(key + ':' + value)
        return '{' + ','.join(result) + '}'

    @staticmethod
    def loadJsonValues(json_values):

        json_values = InitJsonLoader.add_quotes(json_values)
        values = json.loads(json_values)
        return values