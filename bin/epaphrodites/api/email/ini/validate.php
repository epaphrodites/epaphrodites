<?php

namespace Epaphrodites\epaphrodites\api\email\ini;

use Exception;

trait validate{

    use constant;

        /**
     * Valide les adresses email
     */
    private function validateEmails(array $emails): array {
        $validEmails = [];
        $invalidCount = 0;

        foreach ($emails as $email) {
            if ($this->isValidEmail($email)) {
                $validEmails[] = $email;
            } else {
                $invalidCount++;
            }
        }

        return [$validEmails, $invalidCount];
    }

    /**
     * Configure les paramètres SMTP optimisés
     */
    private function configureSMTP(): void {
        $this->mail->SMTPKeepAlive = true;
        $this->mail->Timeout = 30;
        $this->mail->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ];
    }
   
    /**
     * Vérifie si une adresse email est valide
     */
    private function isValidEmail(string $email): bool {
        return filter_var($email, FILTER_VALIDATE_EMAIL) && 
               checkdnsrr(substr(strrchr($email, "@"), 1), "MX");
    }

    /**
     * Initialise le mailer avec les paramètres de base
     */
    private function initializeMailer(
        string $msgHeader,
        string $msgContent,
        ?string $file
    ): bool {
        try {
            if (!$this->settings()) {
                throw new Exception("SMTP configuration error.");
            }
    
            $this->configureSMTP();
            $this->content($msgHeader, $msgContent);
            
            if ($file !== null) {
                if (!is_readable($file)) {
                    throw new Exception("Attachment file not readable: " . $file);
                }
                $this->mail->addAttachment($file);
            }
    
            return true;
        } catch (Exception $e) {
            $this->handleError($e->getMessage());
            return false;
        }
    }

    /**
     * Traite l'envoi par lots
     */
    private function processBatches(array $contacts): array {
        $result = [
            'success' => true,
            'sent' => 0,
            'failed' => 0,
            'errors' => []
        ];

        foreach (array_chunk($contacts, self::BATCH_SIZE) as $batchIndex => $batch) {
            if (!$this->isWithinRateLimit()) {
                $result['errors'][] = "Rate limit reached at batch $batchIndex";
                break;
            }

            $batchResult = $this->sendBatchWithRetry($batch, $batchIndex);
            $result = $this->mergeBatchResults($result, $batchResult);
            
            $this->adaptiveSleep();
        }

        return $result;
    }

    /**
     * Envoie un lot avec système de retry
     */
    private function sendBatchWithRetry(array $batch, int $batchIndex): array {
        $retryCount = 0;
        $result = ['success' => false, 'sent' => 0, 'failed' => 0, 'errors' => []];

        while (!$result['success'] && $retryCount < self::MAX_RETRIES) {
            try {
                if ($retryCount > 0) {
                    usleep(self::RETRY_DELAY_MS * 1000);
                    $this->resetConnection();
                }

                $this->mail->clearAddresses();
                foreach ($batch as $contact) {
                    $this->mail->addBCC($contact);
                }
                
                if ($this->mail->send()) {
                    $result['success'] = true;
                    $result['sent'] = count($batch);
                    $this->updateRateLimit(count($batch));
                }
            } catch (Exception $e) {
                $retryCount++;
                if ($retryCount >= self::MAX_RETRIES) {
                    $result['failed'] = count($batch);
                    $result['errors'][] = "Batch $batchIndex failed after $retryCount retries";
                }
            }
        }

        return $result;
    }

    /**
     * Vérifie si on est dans les limites de taux d'envoi
     */
    private function isWithinRateLimit(): bool {
        $currentTime = microtime(true);
        $windowStart = $currentTime - self::RATE_LIMIT_WINDOW_SECONDS;
        
        $this->sentEmails = array_filter(
            $this->sentEmails,
            fn($time) => $time >= $windowStart
        );

        return count($this->sentEmails) < self::RATE_LIMIT_EMAILS_PER_HOUR;
    }

    /**
     * Met à jour le compteur de rate limit
     */
    private function updateRateLimit(int $count): void {
        $currentTime = microtime(true);
        array_push($this->sentEmails, ...array_fill(0, $count, $currentTime));
    }

    /**
     * Gestion du délai adaptatif entre les envois
     */
    private function adaptiveSleep(): void {
        $currentTime = microtime(true);
        $elapsed = $currentTime - $this->lastSendTime;
        $targetDelay = self::RATE_LIMIT_WINDOW_SECONDS / self::RATE_LIMIT_EMAILS_PER_HOUR;
        
        if ($elapsed < $targetDelay) {
            usleep(($targetDelay - $elapsed) * 1000000);
        }
        
        $this->lastSendTime = microtime(true);
    }

    /**
     * Réinitialise la connexion SMTP
     */
    private function resetConnection(): void {
        $this->mail->smtpClose();
        $this->mail->getSMTPInstance()->reset();
    }

    /**
     * Nettoie les ressources
     */
    private function cleanup(): void {
        $this->mail->clearAddresses();
        $this->mail->clearAttachments();
        $this->mail->smtpClose();
    }

    /**
     * Gère les erreurs
     */
    private function handleError(string $message): void {
        error_log("[SendMail] " . $message);
    }

    /**
     * Fusionne les résultats des lots
     */
    private function mergeBatchResults(array $total, array $batch): array {
        return [
            'success' => $total['success'] && $batch['success'],
            'sent' => $total['sent'] + $batch['sent'],
            'failed' => $total['failed'] + $batch['failed'],
            'errors' => array_merge($total['errors'], $batch['errors'])
        ];
    }    
}