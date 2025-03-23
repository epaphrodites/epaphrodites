<?php

namespace Epaphrodites\epaphrodites\api\email;

use Exception;
use Epaphrodites\epaphrodites\api\email\ini\config;
use Epaphrodites\epaphrodites\api\email\ini\validate;

class SendMail extends config
{
    use validate;

    /**
     * Envoie des emails à plusieurs destinataires avec gestion des erreurs et des performances
     * 
     * @param array $contacts Liste des adresses email des destinataires
     * @param string $msgHeader Sujet de l'email
     * @param string $msgContent Contenu de l'email
     * @param string|null $file Chemin du fichier à joindre (optionnel)
     * @return array Résultat détaillé de l'envoi
     */
    public function sendEmail(
        array $contacts = [],
        string $msgHeader = '',
        string $msgContent = '',
        string|null $file = null
    ): array {
        $result = [
            'success' => false,
            'sent' => 0,
            'failed' => 0,
            'invalid' => 0,
            'errors' => []
        ];

        if (empty($contacts)) {
            $result['errors'][] = 'No contacts provided';
            return $result;
        }

        try {
            [$validContacts, $invalidCount] = $this->validateEmails($contacts);
            $result['invalid'] = $invalidCount;

            if (empty($validContacts)) {
                throw new Exception("No valid email addresses provided.");
            }

            if (!$this->initializeMailer($msgHeader, $msgContent, $file)) {
                throw new Exception("Failed to initialize mailer");
            }

            $sendResult = $this->processBatches($validContacts);
            
            return $result = array_merge($result, $sendResult);

        } catch (Exception $e) {
            $this->handleError("Critical error: " . $e->getMessage());
            $result['errors'][] = $e->getMessage();
        } finally {
            $this->cleanup();
        }

        return $result;
    }
}