import re

# Définition des mots clés et des réponses associées
keyword_responses = {
    "bonjour salut hey": "Bonjour ! Comment puis-je vous aider aujourd'hui ?",
    "horaires d'ouverture": "Nos horaires d'ouverture sont du lundi au vendredi de 9h à 18h.",
    "adresse du magasin": "Notre adresse est 123 rue Exemple, 75000 Paris.",
    "merci beaucoup": "Je vous en prie, c'est un plaisir de vous aider !",
    "au revoir": "Au revoir ! N'hésitez pas à me recontacter si vous avez d'autres questions."
}

# Fonction pour calculer le coefficient de Jaccard entre deux ensembles de mots
def jaccard_similarity(set1, set2):
    intersection = len(set1.intersection(set2))
    union = len(set1.union(set2))
    return intersection / union

# Fonction pour obtenir la réponse basée sur les mots clés
def get_keyword_response(user_input):
    for keyword, response in keyword_responses.items():
        if re.search(r'\b' + keyword + r'\b', user_input, re.IGNORECASE):
            return response
    return None

# Fonction pour obtenir la réponse basée sur la similarité des ensembles de mots
def get_similarity_response(user_input):
    max_similarity = 0
    best_response = None
    user_words = set(user_input.lower().split())
    for keyword, response in keyword_responses.items():
        keyword_words = set(keyword.lower().split())
        similarity = jaccard_similarity(user_words, keyword_words)
        if similarity > max_similarity:
            max_similarity = similarity
            best_response = response
    if max_similarity >= 0.5:
        return best_response
    return None

# Fonction principale du chatbot
def chatbot():
    print("Chatbot: Bonjour ! Comment puis-je vous aider ?")
    while True:
        user_input = input("Utilisateur: ")
        
        # Obtenir la réponse basée sur les mots clés
        keyword_response = get_keyword_response(user_input)
        
        # Si aucune réponse trouvée, obtenir la réponse basée sur la similarité
        if not keyword_response:
            keyword_response = get_similarity_response(user_input)
        
        # Si une réponse est trouvée, l'afficher
        if keyword_response:
            print("Chatbot:", keyword_response)
        else:
            print("Chatbot: Désolé, je ne comprends pas votre demande. Pouvez-vous reformuler ?")

# Lancer le chatbot
chatbot()