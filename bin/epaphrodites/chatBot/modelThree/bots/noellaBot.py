import sys
sys.path.append('bin/epaphrodites/chatBot/mainConfig/')
sys.path.append('bin/epaphrodites/chatBot/modelThree/botConfig/')
from normalizedWords import NormalizedWords
from noellaBotCore import NoellaBotCore

class NoellaBot:
    
    @staticmethod
    def getUsersMessages(login, initMessage):
        
        normalizedMessage = NormalizedWords.normalizeUsersMessages(initMessage)
        
        result = NoellaBotCore.listenUsersMessage(login, initMessage, normalizedMessage)
        
        return result