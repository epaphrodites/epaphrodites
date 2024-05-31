
from dateTimes import DateTimes

class WebHook:
    
    @staticmethod
    def listen( question, actionName, checkInSentences ):
        
        actions = {
            'generate_controller': '',
            'clean_data': '',
            'get_date': DateTimes.getdate(),
            'get_hour': DateTimes.getHour()
        }
        
        lunchFunction = actions.get(actionName)
        
        if lunchFunction:
            return lunchFunction