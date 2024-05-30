

class WebHook:
    
    @staticmethod
    def listen( question, actionName, checkInSentences ):
        
        actions = {
            'generate_controller': ''
        }
        
        action = actions.get(actionName)
        if action:
            action()