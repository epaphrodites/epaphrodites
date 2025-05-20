
import sys
import time
import json
import os
sys.path.append('bin/epaphrodites/python/config/')
sys.path.append('bin/epaphrodites/chatBot/ragFaissModel/')

sys.path.append(os.path.abspath(os.path.join(os.path.dirname(__file__), '../../../..')))

from botCore import BotCore
from initJsonLoader import InitJsonLoader

class LunchRagFaissModel:

    sessions_activity = {}
    
    @staticmethod
    def cleanup_old_sessions(max_idle_time=3600, max_sessions=50):

        current_time = time.time()
        
        inactive_sessions = []
        
        for user_id, last_access in LunchRagFaissModel.sessions_activity.items():
            
            if current_time - last_access > max_idle_time:
                
                inactive_sessions.append(user_id)
        
        for user_id in inactive_sessions:
  
            del LunchRagFaissModel.sessions_activity[user_id]
            
        if len(LunchRagFaissModel.sessions_activity) > max_sessions:

            sorted_sessions = sorted(LunchRagFaissModel.sessions_activity.items(), 
                                    key=lambda x: x[1])
            
            to_remove = sorted_sessions[:len(sorted_sessions) - max_sessions]
            
            for user_id, _ in to_remove:

                del LunchRagFaissModel.sessions_activity[user_id]
    
    @staticmethod
    def askQuestions(message, user_id="default", stream=True):

        LunchRagFaissModel.sessions_activity[user_id] = time.time()
        
        LunchRagFaissModel.cleanup_old_sessions()
        
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

    
#LunchRagFaissModel.askQuestions(json_datas['msg'], user_id=user_id, stream=True)