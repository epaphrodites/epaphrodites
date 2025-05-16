import sys
sys.path.append('bin/epaphrodites/python/config/')
sys.path.append('bin/epaphrodites/chatBot/ragFaissModel/')
from botCore import BotCore
from initJsonLoader import InitJsonLoader

class lunchRagFaissModel:

    @staticmethod
    def askQuestions(message):
        bot = BotCore()
        result = bot.ask(message)
        return result


if __name__ == '__main__':  
    
    json_values = sys.argv[1]
    
    json_datas = InitJsonLoader.loadJsonValues(json_values)
    
    if 'msg' not in json_datas:
        
        print("The JSON file must contain 'msg'.")
        
        sys.exit(1) 
    
    result = lunchRagFaissModel.askQuestions("Comment generer une base de donnees")
    
    print(result)