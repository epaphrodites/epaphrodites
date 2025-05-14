import sys
sys.path.append('bin/epaphrodites/python/config/')
sys.path.append('bin/epaphrodites/chatBot/ragFaissModel/')
from botCore import BotCore  # Changed botCore to BotCore
from initJsonLoader import InitJsonLoader

class LunchRagFaissModel:  # Class name adjusted to follow Python conventions (CamelCase)

    @staticmethod
    def ask_questions(message):  # Renamed method to follow PEP 8 (snake_case)
        bot = BotCore()  # Changed botCore to BotCore
        result = bot.ask(message)
        return result


if __name__ == '__main__':  
    if len(sys.argv) < 2:
        print("Please provide a JSON file as an argument.")
        sys.exit(1)
    
    json_values = sys.argv[1]
    
    json_datas = InitJsonLoader.loadJsonValues(json_values)
    
    if 'msg' not in json_datas:
        print("The JSON file must contain 'msg'.")
        sys.exit(1) 
    
    result = LunchRagFaissModel.ask_questions(json_datas['msg'])
    
    print(result)