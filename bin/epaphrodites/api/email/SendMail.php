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
        array $contacts = [], 
        string $msgHeader = '', 
        string $msgContent = '', 
        string|null $file = null
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
        string $text, 
        string $lang
    ){

        if (empty($text)&&empty($lang)) {
            throw new Exception("verify your content text ans abrevation language");
        }

        return static::initConfig()['python']->executePython('translatWords', ["text" => $text , "lang"=>$lang ]);
    }  
    
    public function sendEmail(
        array $contacts = [], 
        string $msgHeader = '', 
        string $msgContent = '', 
        string|null $file = null
    ): bool 
    {
        if(__EMAIL_METHOD__ == 'python'){
            return false;
        }else{
            return $this->sendEmailByPhp($contacts, $msgHeader, $msgContent, $file);
        }
    }
}
