<?php

namespace Epaphrodite\epaphrodite\api\email;

use Epaphrodite\epaphrodite\api\email\ini\config;

class SendMail extends config
{

    /**
     * Send Message
     * @param null|array $contacts
     * @param null|string $msgHeader
     * @param null|string $msgContent
     * @param null|string $file
     * @return bool
     */
    public function sendEmail(?array $contacts = null, ?string $msgHeader = null, ?string $msgContent = null, ?string $file = null)
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
