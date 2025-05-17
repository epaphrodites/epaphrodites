
import sys
import time
import json
sys.path.append('bin/epaphrodites/python/config/')
sys.path.append('bin/epaphrodites/chatBot/ragFaissModel/')
from botCore import BotCore
from initJsonLoader import InitJsonLoader

class lunchRagFaissModel:

    sessions_activity = {}
    
    @staticmethod
    def cleanup_old_sessions(max_idle_time=3600, max_sessions=50):

        current_time = time.time()
        inactive_sessions = []
        
        for user_id, last_access in lunchRagFaissModel.sessions_activity.items():
            if current_time - last_access > max_idle_time:
                inactive_sessions.append(user_id)
        
        for user_id in inactive_sessions:
  
            del lunchRagFaissModel.sessions_activity[user_id]
            
        if len(lunchRagFaissModel.sessions_activity) > max_sessions:

            sorted_sessions = sorted(lunchRagFaissModel.sessions_activity.items(), 
                                    key=lambda x: x[1])
            
            to_remove = sorted_sessions[:len(sorted_sessions) - max_sessions]
            for user_id, _ in to_remove:

                del lunchRagFaissModel.sessions_activity[user_id]
    
    @staticmethod
    def askQuestions(message, user_id="default", stream=True):

        lunchRagFaissModel.sessions_activity[user_id] = time.time()
        
        lunchRagFaissModel.cleanup_old_sessions()
        
        bot = BotCore.get_instance(user_id)
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
    
    user_id = json_datas.get('user_id', 'default')
    
    lunchRagFaissModel.askQuestions(json_datas['msg'], user_id=user_id, stream=True)