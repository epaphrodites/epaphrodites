import json
import re

class InitJsonLoader:
    
    @staticmethod
    def add_quotes(string):
        # Supprimer les accolades initiales et finales
        string = string.strip('{}')
        
        # Trouver toutes les paires clé-valeur
        pairs = re.findall(r'(\w+): ([\w]+)', string)
        
        # Créer un dictionnaire avec les clés et valeurs correctes
        data = {}
        for key, value in pairs:
            # Traiter les valeurs spéciales (true, false, null)
            if value == 'true':
                processed_value = True
            elif value == 'false':
                processed_value = False
            elif value == 'null':
                processed_value = None
            else:
                # Traiter les autres valeurs comme des chaînes
                processed_value = value
                
            data[key] = processed_value
        
        # Convertir le dictionnaire en chaîne JSON
        json_string = json.dumps(data, ensure_ascii=False)
        
        return json_string

    @staticmethod
    def loadJsonValues(json_values):

       # json_values = InitJsonLoader.add_quotes(json_values)
        
       # json_values = json.loads(json_values)
        
        return json_values