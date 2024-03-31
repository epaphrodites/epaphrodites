import random

class DefaultMessages:
    
    @staticmethod
    def defaultThankMessages(lang):
        messages =[]
        if lang == 'fr':
            messages = [
                "Merci ! J'ai appris de nouvelles réponses !",
                "Merci ! J'ai acquis de nouvelles connaissances !",
                "Merci ! Je me suis enrichi de nouvelles réponses !",
                "Merci ! De nouvelles informations ont enrichi mes connaissances !"
            ]
        else:
            messages = [
                "Thank you! I learned new answers!",
                "Thank you! I've acquired new knowledge!",
                "Thank you! I have enriched myself with new answers!",
                "Thank you! New information has enriched my knowledge!"
            ]
            
        return random.choice(messages)
    
    @staticmethod
    def defaultTeachMessages(lang):
        messages =[]
        if lang == 'fr':
            messages = [
                "Je suis désolé, mais je ne connais pas la réponse. Pourriez-vous m'instruire ? Si oui, veuillez saisir uniquement les réponses, en les séparant par un '|'. Sinon, écrivez 'stop' pour continuer.",
                "Navré, je ne détiens pas la réponse à cette question. Serait-il possible de m'éclairer ? Si c'est le cas, merci d'entrer les réponses en les distinguant par le symbole '|'. Sinon, écrivez 'stop' pour continuer.",
                "Excusez-moi, mais je n'ai pas la réponse. Pouvez-vous m'apprendre ? Si oui, insérez les réponses en les séparant avec un '|'. Sinon, écrivez 'stop' pour continuer."
            ]
        else:
            messages = [
                "I'm sorry, but I do not know the answer. Could you teach me? If yes, please only enter the answers, separating them with a '|'. Otherwise, write 'stop' to continue.",
                "I'm sorry, I do not have the answer to this question. Would it be possible to enlighten me? If so, please enter the answers, distinguishing them by the symbol '|'. Otherwise, write 'stop' to continue.",
                "Excuse me, but I do not have the answer. Can you teach me? If yes, insert the answers separating them with a '|'. Otherwise, write 'stop' to continue."
            ]
            
        return random.choice(messages)
    
    @staticmethod
    def botDefaultAnswers(lang):
        messages = []
        if lang == 'fr':
            messages = [
                "Je suis une IA d'assistance professionnelle. Je ne gère pas ce type d'informations.",
                "Je suis un modèle de langage et je ne peux pas vous aider avec cette question.",
            ]
        else:
            messages = [
                "I am a professional assistance AI. I do not handle this type of information.",
                "I am a language model and I cannot assist you with this question.",
            ]
            
        return random.choice(messages)
    
    @staticmethod
    def defaultInitMessages(lang):
        messages = []
        if lang == 'fr':
            messages = [
                "Merci, nous pouvons poursuivre notre conversation.",
                "Je vous remercie, nous sommes prêts à continuer nos échanges.",
                "Merci, nous pouvons reprendre notre dialogue.",
            ]
        else:
            messages = [
                "Thank you, we can continue our conversation.",
                "I thank you, we are ready to continue our exchanges.",
                "Thank you, we can resume our dialogue.",
            ]

        return random.choice(messages)