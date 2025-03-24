import sys
import json
import smtplib
from email.mime.text import MIMEText
from email.mime.multipart import MIMEMultipart

class SendEmail:
    def __init__(self):
        self.config = self.charger_configuration()
        self.smtp_server = self.config.get("HOST")
        self.smtp_port = int(self.config.get("PORT", 587))
        self.email_expediteur = self.config.get("USER")
        self.mot_de_passe = self.config.get("PASSWORD")

        if not all([self.smtp_server, self.smtp_port, self.email_expediteur, self.mot_de_passe]):
            raise ValueError("Erreur : Paramètres SMTP manquants.")

    @staticmethod
    def charger_configuration():
        fichier_config = "bin/config/email.ini"
        config = {}

        try:
            with open(fichier_config, "r") as fichier:
                for ligne in fichier:
                    if "=" in ligne:
                        cle, valeur = ligne.strip().split("=", 1)
                        config[cle] = valeur
        except FileNotFoundError:
            print("Erreur : Le fichier de configuration n'existe pas.")
        except Exception as e:
            print(f"Erreur lors de la lecture du fichier de configuration : {e}")

        return config

    def envoyer_email(self, destinataires, contenu, objet, cc=None, bcc=None):
        if not isinstance(destinataires, list):
            destinataires = [destinataires]

        cc = cc or []
        bcc = bcc or []

        all_recipients = destinataires + cc + bcc

        msg = MIMEMultipart()
        msg['From'] = self.email_expediteur
        msg['To'] = ", ".join(destinataires)
        msg['Cc'] = ", ".join(cc)
        msg['Subject'] = objet

        msg.attach(MIMEText(contenu, 'plain'))

        try:
            with smtplib.SMTP(self.smtp_server, self.smtp_port) as server:
                server.starttls()
                server.login(self.email_expediteur, self.mot_de_passe)
                server.sendmail(self.email_expediteur, all_recipients, msg.as_string())

            print("E-mail envoyé avec succès à :", ", ".join(destinataires))
        except Exception as e:
            print("Erreur lors de l'envoi de l'e-mail :", e)


if __name__ == '__main__':
    
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
        email_sender = SendEmail()
        email_sender.envoyer_email(
            destinataires=json_datas['destinataire'],
            contenu=json_datas['contenu'],
            objet=json_datas['objet'],
            cc=json_datas.get('cc', []),
            bcc=json_datas.get('bcc', [])
        )

        print("E-mail envoyé avec succès !")

    except ValueError as e:
        print(str(e))
        sys.exit(1)