<?php

namespace Epaphrodite\epaphrodite\define\lang\eng;

class SetEnglishTextMessages
{
    private $AllMessages;

    public function SwithAnswers($MessageCode)
    {

        $this->AllMessages[] =
        [
            'language' => 'english',
            '403-title' => 'ERROR 403',
            '404-title' => 'ERROR 404',
            '419-title' => 'ERROR 419',
            '500-title' => 'ERROR 500',
            'session_name' => _SESSION_,
            'token_name' => _CRSF_TOKEN_,
            'back' => "Return to homepage",
            '403' => "Restricted access!!!",
            'author' => 'Epaphrodite Agency',
            '500' => 'Internal server error',
            '404' => "Oops! No page found!!!",
            'site-title' => 'HOME | EPAPHRODITE',
            'mdpnotsame' => "Incorrect password",
            'fileempty' => "No file selected!!!",
            'description' => 'epaphrodite agency',
            '419' => "Your session has expired!!!",
            'version' => 'EPAPHRODITE V0.1 (PHP 8.2.11)',
            'error_text' => 'txt error epaphrodite',
            'denie' => "Processing not possible!!!",
            'noformat' => "The file format is incorrect!",
            'login-wrong' => "Login or password incorrect",
            'connexion' => "Please reconnect again, please!",
            'succes' => "Processing completed successfully!!!",
            'no-identic' => "Sorry, the passwords do not match.",
            'vide' => "Please fill in all fields correctly, please!!!",
            'no-data' => "Sorry, no information matches your request.",
            'erreur' => "Sorry, an issue occurred during processing!!!",
            'done' => "Congratulations, your registration was successful!!!",
            'rightexist' => "Sorry, this right already exists for this user.",
            'tailleauto' => "The file size exceeds the allowed limit of 500 KB.",
            'send' => "Congratulations, your message has been successfully sent!!!",
            'errorsending' => "Sorry, an issue occurred while sending your message!!!",
            'denie_action' => "Processing not possible!!! You do not have authorization to perform this action.",
            'keywords' => "Epaphrodite Agency, Creation; website; digital; community manager; logo; visual identity; marketing; communication;",
        ];

        return $this->AllMessages[0][$MessageCode];
    }
}
