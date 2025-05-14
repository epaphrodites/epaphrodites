import sys
import logging
import base64
import json
sys.path.append('bin/epaphrodites/python/config/')
sys.path.append('bin/epaphrodites/chatBot/ragFaissModel/')
from botCore import BotCore
from initJsonLoader import InitJsonLoader

# Configurer le logging pour le débogage
logging.basicConfig(level=logging.DEBUG, format='%(asctime)s - %(levelname)s - %(message)s')
logger = logging.getLogger(__name__)

class LunchRagFaissModel:
    @staticmethod
    def ask_questions(message):
        """Diffuse une réponse en streaming pour le message donné."""
        logger.debug(f"Processing message: {message}")
        bot = BotCore()
        try:
            for chunk in bot.stream_answer(message):
                logger.debug(f"Received chunk: {chunk}")
                yield chunk
        except Exception as e:
            logger.error(f"Error in streaming response: {str(e)}")
            yield f"Erreur lors de la diffusion de la réponse : {str(e)}"

if __name__ == '__main__':
    if len(sys.argv) < 2:
        logger.error("No JSON file provided")
        print("Please provide a JSON file as an argument.")
        sys.exit(1)
    
    json_values = sys.argv[1]
    logger.debug(f"Loading JSON file: {json_values}")
    
    try:
        # Décoder le JSON base64 si nécessaire
        if json_values.startswith("ey"):
            json_str = base64.b64decode(json_values).decode('utf-8')
            json_datas = json.loads(json_str)
        else:
            json_datas = InitJsonLoader.loadJsonValues(json_values)
        
        if 'msg' not in json_datas:
            logger.error("JSON file does not contain 'msg' key")
            print("The JSON file must contain 'msg'.")
            sys.exit(1)
        
        # Corriger l'encodage du message
        message = json_datas['msg'].encode('utf-8').decode('utf-8')
        logger.debug(f"Message from JSON: {message}")
        
        try:
            for chunk in LunchRagFaissModel.ask_questions(message):
                print(chunk, end='', flush=True)
                logger.debug(f"Printed chunk: {chunk}")
            print()
            logger.debug("Streaming completed")
        except Exception as e:
            logger.error(f"Error during streaming: {str(e)}")
            print(f"\nErreur lors de l'affichage de la réponse : {str(e)}")
    except Exception as e:
        logger.error(f"Error loading JSON: {str(e)}")
        print(f"Erreur lors du chargement du JSON : {str(e)}")
        sys.exit(1)