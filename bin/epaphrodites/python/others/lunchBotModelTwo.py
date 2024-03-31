import sys
import json
sys.path.append('bin/epaphrodites/chatBot/modeleTwo/bots/')
from herediaBot import HerediaBot

class LunchBotModelTwo:

    @staticmethod
    def func_lunchBotModelTwo(login, message, learn):
        
       result = HerediaBot.getUsersMessages(login, message, learn)
       return result

    @staticmethod
    def loadJsonValues(json_values):

        values = json.loads(json_values)
        return values

if __name__ == '__main__':  
    
    json_values = sys.argv[1]
    
    json_datas = LunchBotModelTwo.loadJsonValues(json_values)
    
    if 'login' not in json_datas or 'userMessages' not in json_datas:
        print("The JSON file must contain 'login' and 'userMessages'.")
        sys.exit(1)    
    
    result = LunchBotModelTwo.func_lunchBotModelTwo(json_datas['login'], json_datas['userMessages'], json_datas['learn'])
    
    print(result)        