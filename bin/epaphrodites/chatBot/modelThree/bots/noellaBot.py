import sys
sys.path.append('bin/epaphrodites/chatBot/modelThree/botConfig/')
from normalizedWords import NormalizedWords

class NoellaBot:
    
    @staticmethod
    def getUsersMessages(login, userMessage):
        
        message = NormalizedWords.normalizeUsersMessages(userMessage)
        
        return message