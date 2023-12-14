<?php

namespace Epaphrodites\epaphrodites\define\lang\fr;

class SetFrenchTextMessages
{
    private $AllMessages;

    public function SwithAnswers($MessageCode)
    {

        $this->AllMessages[] =
            [
                'language' => 'french',
                '403-title' => 'ERREUR 403',
                '404-title' => 'ERREUR 404',
                '419-title' => 'ERREUR 419',
                'session_name' => _SESSION_,
                'token_name' => _CRSF_TOKEN_,
                '500-title' => 'ERREUR 500',
                '403' => 'Acces restreint !!!',
                '500' => 'Internal server error',
                'author' => 'Agence Epaphrodites',
                'back' => "Retour page d'accueil",
                'description' => 'agence epaphrodites',
                'version' => 'EPAPHRODITES V0.1 (PHP 8.2.11)',
                'denie' => "Traitement impossible !!!",
                '419' => 'Votre session a expirée !!!',
                'mdpnotsame' => 'Mot de passe incorrect',
                '404' => 'Oops! Aucune page trouvée !!!',
                'site-title' => 'ACCUEIL | EPAPHRODITES',
                'error_text' => 'Erreur txt epaphrodites',
                'fileempty' => 'Aucun fichier selectionné !!!',
                'noformat' => 'Le format du fichier incorrecte !',
                'succes' => 'Traitement effectué avec succès !!!',
                'login-wrong' => 'Login ou mot de passe incorrecte',
                'connexion' => 'Veuillez vous reconnecter à nouveau svp !',
                'vide' => 'Veuillez remplir correctement tous champs svp !!!',
                'rightexist' => 'Désolé ce droit existe déjà pour ce utilisateur',
                'no-identic' => 'Désolé les mots de passes ne sont pas identiques',
                'erreur' => "Désolé un problème est survenu lors du traitement !!!",
                'send' => 'Félicitation votre message a été envoyé avec succès !!!',
                'no-data' => 'Désolé aucune information ne correspond à votre demande',
                'done' => 'Félicitation votre inscription a été effectué avec succès !!!',
                'tailleauto' => "La taille du fichier dépasse la limite autorisée de 500 Ko",
                'errorsending' => "Désolé un problème est survenu lors de l'envoi de votre message !!!",
                'denie_action' => "Traitement impossible !!! Vous n'avez pas l'autorisation d'effectué cette action.",
                'keywords' => "Agence epaphrodites  , Création; site web; digitale; community manager; logo; identité visuelle; marketing; communication;",
            ];

        return $this->AllMessages[0][$MessageCode];
    }
}
