import sys
import time
import json
import os

sys.path.append('bin/epaphrodites/python/config/')
sys.path.append('bin/epaphrodites/chatBot/ragFaissModel/')

from botCore import modelBotCore
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
        
        bot = modelBotCore.get_instance(user_id)
        
        result = bot.ask(message, stream=stream)
        
        if stream:
            # Solution Windows-compatible pour le streaming
            response_chunks = []
            try:
                for chunk in result:
                    if "response" in chunk:
                        chunk_text = chunk["response"]
                        response_chunks.append(chunk_text)
                        # Utilisation de print au lieu de sys.stdout.write pour Windows
                        print(chunk_text, end='', flush=True)
                
                # Retour à la ligne final
                print()  # Équivalent à sys.stdout.write("\n")
                
                # Optionnel : retourner la réponse complète
                return ''.join(response_chunks)
                
            except Exception as e:
                print(f"\n❌ Erreur lors du streaming: {e}")
                return ""
        else:
            return result

if __name__ == '__main__':
    
    # Vérification des arguments
    if len(sys.argv) < 2:
        print("❌ Usage: python lunchRagFaissModel.py <json_values>")
        sys.exit(1)
    
    json_values = sys.argv[1]
    
    try:
        json_data = InitJsonLoader.loadJsonValues(json_values)
    except Exception as e:
        print(f"❌ Erreur lors du chargement JSON: {e}")
        sys.exit(1)
    
    if 'prompt' not in json_data:
        print("❌ Le fichier JSON doit contenir 'prompt'.")
        sys.exit(1)
    
    user_id = json_data.get('user_id', 'default')
    
    # Configuration de l'encodage pour Windows
    if os.name == 'nt':  # Windows
        try:
            # Forcer l'encodage UTF-8 pour Windows
            import locale
            import codecs
            
            # Configurer stdout pour UTF-8
            if hasattr(sys.stdout, 'buffer'):
                sys.stdout = codecs.getwriter('utf-8')(sys.stdout.buffer, 'strict')
            
        except Exception as e:
            print(f"⚠️ Avertissement encodage Windows: {e}")
    
    # Lancement de la question
    try:
        LunchRagFaissModel.askQuestions(json_data['prompt'], user_id=user_id, stream=True)
    except KeyboardInterrupt:
        print("\n🔄 Interruption utilisateur")
        sys.exit(0)
    except Exception as e:
        print(f"\n❌ Erreur lors de l'exécution: {e}")
        sys.exit(1)