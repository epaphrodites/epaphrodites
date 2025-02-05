<?php

namespace Epaphrodites\epaphrodites\api\email;

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
    public function sendEmail(
        array|null $contacts = null, 
        string|null $msgHeader = null, 
        string|null $msgContent = null, 
        string|null $file = null
    ):bool
    {
        if ($this->settings() === true) {
            foreach ($contacts as $contact) {
                $this->mail->addAddress($contact);
            }

            // Attachments
            // if ($file != null) {
            //    $this->mail->addAttachment(_DIR_FILES_, $file);
            // }

            $this->content($msgHeader, $msgContent);

            if ($this->mail->send()) {
                return true;
            } else {
                return false;
            }
        }else{
            return false;
        }
    }
}
