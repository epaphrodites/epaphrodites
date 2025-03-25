import os
import sys
import json
import base64
import smtplib
import configparser
from email.mime.multipart import MIMEMultipart
from email.mime.text import MIMEText
from email.mime.application import MIMEApplication

sys.path.append('bin/epaphrodites/python/config/')
from initJsonLoader import InitJsonLoader

# Résolution du chemin du fichier de configuration
#CONFIG_PATH = os.path.join(os.path.dirname(__file__), '..', 'bin/config', 'email.ini')

class SendEmail:
    @staticmethod
    def configurer_email():

        return {
            "server": "smtp.hostinger.com",
            "port": 587,
            "users": 'smtp@epaphrodite.org',
            "password": "5?zzC66GSw#E",
            "no_replay": "no-reply@epaphrodite.org"
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
    
    json_values_encoded = sys.argv[1]

    json_values_decoded = base64.b64decode(json_values_encoded).decode("utf-8")
        
    json_datas = json.loads(json_values_decoded)
    
    try:
        SendEmail.envoyer_email(
            destinataires=json_datas['destinataire'],
            sujet=json_datas['objet'],
            contenu=json_datas['contenu']
        )
        print("E-mail envoyé avec succès !")
    
    except ValueError as e:
        print(str(e))
        sys.exit(1)

if __name__ == "__main__":
    main()