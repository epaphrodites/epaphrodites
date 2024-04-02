import json
import re

class InitJsonLoader:
    
    @staticmethod
    def add_quotes(string):
        clean_string = re.sub(r'\s*"\s*', '"', string)
    
        # Utiliser une expression régulière pour extraire les paires clé-valeur
        pairs = re.findall(r'"([^"]+)":"([^"]+)"', clean_string)
        
        # Construire un dictionnaire à partir des paires extraites
        data = {key: value for key, value in pairs}
        
        # Convertir le dictionnaire en chaîne JSON
        json_string = json.dumps(data)
        
        return json_string

    @staticmethod
    def loadJsonValues(json_values):

        json_values = InitJsonLoader.add_quotes(json_values)
        
        return json_values