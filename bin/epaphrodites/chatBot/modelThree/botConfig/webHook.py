
class WebHook:
    
    @staticmethod
    def listen( question, actionName, checkInSentences ):
        
        actions = {
            'generate_controller': '',
            'clean_data': '',
            'get_date': '',
            'get_hour': ''
        }
        
        lunchFunction = actions.get(actionName)
        if lunchFunction:
            lunchFunction()