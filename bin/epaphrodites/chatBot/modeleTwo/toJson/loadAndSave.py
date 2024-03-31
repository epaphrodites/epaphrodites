import sys
import json

class LoadAndSave:
    
    @staticmethod
    def load_knowledge_base(file_path: str) -> dict:
        with open(file_path, 'r') as file:
            data = json.load(file)
            return data
        
    @staticmethod
    def get_last_learn_datas(conversations , login) -> dict | None:
        for conversation in reversed(conversations):
            if conversation.get('login') == login:
                return conversation
        return None
        
    @staticmethod
    def save_knowledge_base(file_path: str, data: dict) -> dict:
        with open(file_path, 'w', encoding='utf-8') as file:
            json.dump(data, file, indent=2)