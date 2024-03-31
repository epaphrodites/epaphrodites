import sys
sys.path.append('bin/epaphrodites/chatBot/modeleTwo/toJson/')
from constants import _SAVE_TO_JSON_FILE_
from loadAndSave import LoadAndSave
from mainConfig import MainConfig as config
from botDictionnary import BotDictionnary as Dictionaries

class DetectLanguages:
    
    @staticmethod
    def detect_language_with_dictionary(usersMessages, login):
        if not isinstance(usersMessages, list):
            usersMessages = [usersMessages]
         
        getLastMessage = LoadAndSave.load_knowledge_base(_SAVE_TO_JSON_FILE_)  
        last_language = LoadAndSave.get_last_learn_datas(getLastMessage, login)
        dictionaries = Dictionaries.get_dictionary()

        lang_counts = {}
        for word in usersMessages:
            for lang, dictionary_set in dictionaries.items():
                for dict_word in dictionary_set:
                    if config.similarity(word, dict_word) > 0.8: 
                        if lang in lang_counts:
                            lang_counts[lang] += config.similarity(word, dict_word)
                        else:
                            lang_counts[lang] = config.similarity(word, dict_word)

        if lang_counts:
            return max(lang_counts, key=lang_counts.get)
        elif last_language:
            return last_language['language']
        return "eng"
