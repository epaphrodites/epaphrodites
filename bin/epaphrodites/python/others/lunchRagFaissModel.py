import sys
sys.path.append('bin/epaphrodites/python/config/')
sys.path.append('bin/epaphrodites/chatBot/ragFaissModel/')
from botCore import botCore
from initJsonLoader import InitJsonLoader

class lunchRagFaissModel:

    @staticmethod
    def askQuestions(message):
        
       result = botCore.ask(message)
       
       return result

if __name__ == '__main__':  
    
    json_values = sys.argv[1]
    
    json_datas = InitJsonLoader.loadJsonValues(json_values)
    
    if 'userMessages' not in json_datas :
        
        print("The JSON file must contain 'userMessages'.")
        
        sys.exit(1) 
    
    result = lunchRagFaissModel.askQuestions(json_datas['userMessages'])
    
    print(result)        