<?php

namespace Epaphrodites\epaphrodites\api\email;

use Exception;
use Epaphrodites\epaphrodites\api\email\ini\config;

class SendMail extends config
{

    /**
     * Send Email
     * @param null|array $contacts
     * @param null|string $msgHeader
     * @param null|string $msgContent
     * @param null|string $file
     * @return bool
     */
    private function sendEmailByPhp(
        array $contacts, 
        string $msgHeader, 
        string $msgContent, 
        string|null $file
    ): bool 
    {
        try {
            if (!$this->settings()) {
                throw new Exception("SMTP configuration error.");
            }
    
            $this->mail->SMTPKeepAlive = true;
    
            foreach ($contacts as $contact) {
                $this->mail->clearAddresses();
                $this->mail->addAddress($contact);
    
                if (!empty($file) && file_exists($file)) {
                    $this->mail->addAttachment($file);
                }
    
                $this->content($msgHeader, $msgContent);

                if (!$this->mail->send()) {
                    throw new Exception("SMTP error: " . $this->mail->ErrorInfo);
                }

                usleep(500000); 
            }
    
            $this->mail->smtpClose();
            return true;
        } catch (Exception $e) {
            error_log("Email sending error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * To use this function, you must install python 3
     * and run this commande "pip install googletrans==4.0.0-rc1"
     * @param mixed $text
     * @param string $lang
     * @return mixed
     */
    private function sendEmailByPython(
        array $contacts = [], 
        string $msgHeader = '', 
        string $msgContent = '', 
    ){

        if (empty($contacts)&&empty($msgContent)) {
            throw new Exception("verify your content text ans abrevation language");
        }

        return $this->pip()->executePython('senEmail', ["destinataire" => [$contacts], "contenu"=>$msgContent, "objet"=>$msgHeader ]);
    }  
    
    public function sendEmail(
        array $contacts, 
        string $msgHeader, 
        string $msgContent, 
        string|null $file = null
    ) 
    {
        if(__EMAIL_METHOD__ == 'python'){

            return $this->sendEmailByPython($contacts, $msgHeader, $msgContent);
        }else{

            return $this->sendEmailByPhp($contacts, $msgHeader, $msgContent, $file);
        }
    }
}
