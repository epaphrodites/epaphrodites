<?php

namespace Epaphrodites\epaphrodites\api\email\ini;

use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class config
{
    public object $mail;

    public function __construct()
    {
        // Initialize the PHPMailer object
        $this->mail = new PHPMailer(true);
    }

    protected function settings()
    {
        try {
            // Enable SMTP debugging (optional)
            $this->mail->SMTPDebug = SMTP::DEBUG_SERVER;

            // Use SMTP for sending emails
            $this->mail->isSMTP();

            // Get email configuration from the ini file
            $config = static::configIni();

            // Set SMTP server details
            $this->mail->Host = $config['HOST'];

            $this->mail->SMTPAuth = true;

            $this->mail->Username = $config['USER'];

            $this->mail->Password = $config['PASSWORD'];

            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

            $this->mail->Port = $config['PORT'];

            // Set the sender's email and name
            $this->mail->setFrom($config['USER'], $config['TITLE']);

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    protected function content(
        string $msgHeader, 
        string $msgContent
    ){
        // Set email format to HTML
        $this->mail->isHTML(true);

        // Set email subject and content
        $this->mail->Subject = $msgHeader;

        $this->mail->Body = $msgContent;
    }

    private static function configIni()
    {
        // Load email configuration from an ini file
        $ini = _DIR_CONFIG_INI_ . "email.ini";

        $content = parse_ini_file($ini, true);

        return $content;
    }
}
