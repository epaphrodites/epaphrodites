import os
import sys
import json
import smtplib
import configparser
from email.mime.multipart import MIMEMultipart
from email.mime.text import MIMEText
from email.mime.application import MIMEApplication

# Résolution du chemin du fichier de configuration
CONFIG_PATH = os.path.join(os.path.dirname(__file__), '..', 'bin/config', 'email.ini')

class SendEmail:
    @staticmethod
    def configurer_email(fichier_config=CONFIG_PATH):
        """
        Lire la configuration email depuis un fichier INI
        :param fichier_config: Chemin du fichier de configuration
        :return: Dictionnaire de configuration
        """
        # Vérification de l'existence du fichier de configuration
        if not os.path.exists(fichier_config):
            raise FileNotFoundError(f"Le fichier de configuration {fichier_config} n'existe pas.")
        
        config = configparser.ConfigParser()
        config.read(fichier_config)
        
        return {
            "server": config['EMAIL']['SERVER'],
            "port": config.getint('EMAIL', 'PORT'),
            "users": config['EMAIL']['USER'],
            "password": config['EMAIL']['PASSWORD'],
            "no_replay": config['EMAIL'].get('HIDE_EMAIL', config['EMAIL']['USER'])
        }

    @staticmethod
    def envoyer_email(destinataires, sujet, contenu, fichiers=None):
        config = SendEmail.configurer_email()
        
        try:
            # Créer le message
            msg = MIMEMultipart()
            msg['From'] = config["users"]  # Utiliser l'adresse de connexion
            msg['To'] = ", ".join(destinataires)
            
            # Ajouter un header Reply-To si vous voulez une adresse de réponse différente
            if config.get('no_replay') and config['no_replay'] != config["users"]:
                msg.add_header('Reply-To', config['no_replay'])
            
            msg['Subject'] = sujet
            
            # Ajouter le contenu
            msg.attach(MIMEText(contenu, 'plain'))
            
            # Ajouter les pièces jointes
            if fichiers:
                for fichier in fichiers:
                    if os.path.exists(fichier):
                        with open(fichier, 'rb') as f:
                            piece_jointe = MIMEApplication(f.read(), _subtype="pdf")
                            piece_jointe.add_header('Content-Disposition', 'attachment', filename=os.path.basename(fichier))
                            msg.attach(piece_jointe)
            
            # Connexion et envoi
            with smtplib.SMTP(config["server"], config["port"]) as server:
                server.starttls()
                server.login(config["users"], config["password"])
                server.send_message(msg)
            
            print("✅ Email envoyé avec succès !")
        
        except Exception as e:
            print(f"❌ Erreur lors de l'envoi de l'email: {e}")
            raise

def main():
    if len(sys.argv) < 2:
        print("Usage: python send_email.py '<json_values>'")
        sys.exit(1)
    
    json_values = sys.argv[1]
    
    try:
        json_datas = json.loads(json_values)
    except json.JSONDecodeError:
        print("Erreur : Le paramètre d'entrée n'est pas un JSON valide.")
        sys.exit(1)
    
    required_fields = ['destinataire', 'contenu', 'objet']
    if not all(field in json_datas for field in required_fields):
        print(f"Erreur : Le JSON doit contenir les champs {', '.join(required_fields)}.")
        sys.exit(1)
    
    try:
        SendEmail.envoyer_email(
            destinataires=json_datas['destinataire'],
            sujet=json_datas['objet'],
            contenu=json_datas['contenu'],
            fichiers=json_datas.get('file', [])
        )
        print("E-mail envoyé avec succès !")
    
    except ValueError as e:
        print(str(e))
        sys.exit(1)

if __name__ == "__main__":
    main()