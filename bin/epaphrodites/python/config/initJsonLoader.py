import json

class InitJsonLoader:
    
    @staticmethod
    def parse_custom_string_correctly(input_string):
        # Supprimer les espaces superflus et les accolades au début et à la fin
        trimmed_str = input_string.strip('{} ')
        # Initialiser les variables
        data = {}
        key = ''
        value = ''
        reading_key = True
        char_buffer = ''
        inside_quotes = False  # Indicateur pour savoir si on est à l'intérieur de guillemets

        for char in trimmed_str:
            if char == '"' and not inside_quotes:
                inside_quotes = True
                char_buffer += char
                continue
            elif char == '"' and inside_quotes:
                inside_quotes = False
                char_buffer += char
                continue
            
            if reading_key:
                if char == ':' and not inside_quotes:
                    key = char_buffer.strip().strip('"')  # Enlever les guillemets si présents
                    char_buffer = ''
                    reading_key = False
                else:
                    char_buffer += char
            else:  # Lecture de la valeur
                if char == ',' and not inside_quotes:
                    value = char_buffer.strip()
                    if not (value.startswith('"') and value.endswith('"')):
                        # Convertir les valeurs spéciales en booléens et None, sinon laisser comme chaîne
                        if value == 'true': value = True
                        elif value == 'false': value = False
                        elif value == 'null': value = None
                        else: value = '"' + value + '"'  # Ajouter des guillemets si absents
                    else:
                        value = value.strip('"')  # Enlever les guillemets pour JSON
                        
                    data[key] = value
                    
                    char_buffer = ''
                    reading_key = True
                else:
                    char_buffer += char
        
        # Gérer la dernière paire clé/valeur
        if char_buffer:
            value = char_buffer.strip()
            if not (value.startswith('"') and value.endswith('"')):
                if value == 'true': value = True
                elif value == 'false': value = False
                elif value == 'null': value = None
                else: value = '"' + value + '"'  # Ajouter des guillemets si absents
            else:
                value = value.strip('"')
            
            data[key] = value

        # Convertir en chaîne JSON en prenant soin de ne pas ajouter de guillemets supplémentaires
        # aux valeurs qui sont déjà des chaînes
        json_data = {k: json.loads(v) if isinstance(v, str) and v.startswith('"') else v for k, v in data.items()}
        return json.dumps(json_data, ensure_ascii=False)


    @staticmethod
    def loadJsonValues(json_values):

        json_values = InitJsonLoader.parse_custom_string_correctly(json_values)
        
        json_values = json.loads(json_values)
        
        return json_values