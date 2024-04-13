import sys
import random
from datetime import datetime
from difflib import get_close_matches
sys.path.append('bin/epaphrodites/chatBot/mainConfig/')
from constants import _LOAD_JSON_FILE_, _SAVE_TO_JSON_FILE_

class NoellaBotCore:
    
    @staticmethod
    def find_best_match(user_question: str, questions: list[str]) -> str | None:
        matches = get_close_matches(user_question, questions, n=1, cutoff=0.8)
        return matches[0] if matches else None
    
    @staticmethod
    def get_answer_for_question(question: str, knowledge_base: dict) -> str | None:
        for q in knowledge_base:
            if question in q["question"]:
                return random.choice(q["answers"])       
    
    @staticmethod
    def listenUsersMessage(login, initMessage, normalizedMessage):
        pass