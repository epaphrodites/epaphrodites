import sys
sys.path.append('bin/epaphrodites/python/config/')
sys.path.append('bin/epaphrodites/chatBot/ragFaissModel/')
from botCore import BotCore
from initJsonLoader import InitJsonLoader

class lunchRagFaissModel:

    @staticmethod
    def askQuestions(message, stream=True):
        bot = BotCore()
        result = bot.ask(message, stream=stream)
        
        if stream:

            for chunk in result:
                if "response" in chunk:

                    sys.stdout.write(chunk["response"])
                    sys.stdout.flush()

            sys.stdout.write("\n")
            return ""
        else:

            return result


if __name__ == '__main__':  
    
    json_values = sys.argv[1]
    
    json_datas = InitJsonLoader.loadJsonValues(json_values)
    
    if 'msg' not in json_datas:
        
        print("The JSON file must contain 'msg'.")
        
        sys.exit(1)
    
    lunchRagFaissModel.askQuestions(json_datas['msg'], stream=True)