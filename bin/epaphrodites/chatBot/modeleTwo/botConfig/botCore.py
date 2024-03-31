import random
from datetime import datetime
from difflib import get_close_matches
from loadAndSave import LoadAndSave
from normalizedWords import NormalizedWords
from detectLanguages import DetectLanguages
from defaultMessages import DefaultMessages
from constants import _LOAD_JSON_FILE_, _SAVE_TO_JSON_FILE_

class BotCore:
    
    @staticmethod
    def find_best_match(user_question: str, questions: list[str]) -> str | None:
        matches = get_close_matches(user_question, questions, n=1, cutoff=0.8)
        return matches[0] if matches else None

    @staticmethod
    def get_answer_for_question(question: str, knowledge_base: dict) -> str | None:
        for q in knowledge_base:
            if q["question"] == question:
                return random.choice(q["answers"])
            
    @staticmethod
    def onlyBotDiscutionTreatment(login, messages):
        now = datetime.now()
        botDate = now.strftime('%d-%m-%Y %H:%M:%S')
        messages = NormalizedWords.normalizeUsersMessages(messages) 
        lang = DetectLanguages.detect_language_with_dictionary(messages.split(), login)
        
        defaultResponseMessages = DefaultMessages.botDefaultAnswers(lang)
        
        knowledge_base = LoadAndSave.load_knowledge_base(_LOAD_JSON_FILE_)
        best_match = BotCore.find_best_match(messages, [q["question"] for q in knowledge_base["questions"]])        
        
        if best_match:
            return {'date':botDate,'language': lang,'question': messages,'answers': BotCore.get_answer_for_question(best_match, knowledge_base) , 'login': login, 'state': False}
        else:
            {'date':botDate,'language': lang,'question': messages,'answers': defaultResponseMessages , 'login': login, 'state': True}  
        

    def botLearnAndDiscutionTreatment(login, messages):
        
        now = datetime.now()
        botDate = now.strftime('%d-%m-%Y %H:%M:%S')
        
        messages = NormalizedWords.normalizeUsersMessages(messages)
        
        lang = DetectLanguages.detect_language_with_dictionary(messages.split(),login)
        
        defaultThankResponseMessages = DefaultMessages.defaultThankMessages(lang)
        
        defaultTeachResponseMessages = DefaultMessages.defaultTeachMessages(lang)
        
        defaultInitResponseMessages = DefaultMessages.defaultInitMessages(lang)
        
        knowledge_base = LoadAndSave.load_knowledge_base(_LOAD_JSON_FILE_)
        
        best_match = BotCore.find_best_match(messages, [q["question"] for q in knowledge_base]) 
        
        if best_match:

            return {'date':botDate,'language': lang,'question': messages,'answers': BotCore.get_answer_for_question(best_match, knowledge_base) , 'login': login, 'state': False}
        else:
            getLastMessage = LoadAndSave.load_knowledge_base(_SAVE_TO_JSON_FILE_)
            lastConversation = LoadAndSave.get_last_learn_datas(getLastMessage , login)

            if isinstance(lastConversation, dict):
                if messages != 'stop':
                    if lastConversation['state'] == True :
                        knowledge_base.append({'language': lang,"question": lastConversation['question'], "answers": messages.split("|")})
                        LoadAndSave.save_knowledge_base(_LOAD_JSON_FILE_, knowledge_base)
                        return {'date':botDate,'language': lang,'question': messages, 'answers': defaultThankResponseMessages, 'login': login, 'state': False}        
                else:
                    return {'date':botDate,'language': lang,'question': messages,'answers': defaultInitResponseMessages , 'login': login, 'state': False} 
                            
            return {'date':botDate,'language': lang,'question': messages,'answers': defaultTeachResponseMessages , 'login': login, 'state': True}      
