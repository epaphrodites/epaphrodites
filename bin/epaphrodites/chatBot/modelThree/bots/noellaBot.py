import sys
sys.path.append('bin/epaphrodites/chatBot/modelThree/botConfig/')
from normalizedWords import NormalizedWords
from botCore import BotCore

class NoellaBot:
    
    @staticmethod
    def getUsersMessages(login, initMessage):
        
        normalizedMessage = NormalizedWords.normalizeUsersMessages(initMessage)
        
        result = BotCore.listenUsersMessage(login, initMessage, normalizedMessage)
        
        return result