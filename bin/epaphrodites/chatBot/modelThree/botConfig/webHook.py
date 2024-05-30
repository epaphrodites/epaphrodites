

class WebHook:
    
    @staticmethod
    def listen( actionName, check ):
        
        actions = {
            'generate_controller': ''
        }
        
        action = actions.get(actionName)
        if action:
            action()
                