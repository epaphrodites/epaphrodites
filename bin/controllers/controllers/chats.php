<?php

namespace Epaphrodites\controllers\controllers;

use Epaphrodites\controllers\switchers\MainSwitchers;

final class chats extends MainSwitchers
{
    private string $ans = '';
    private string $alert = '';
    private array|bool $result = [];

    private object $ajaxTemplate;
    private object $chatBot;

    /**
     * Initialize object properties when an instance is created
     * 
     * @return void
     */
    public final function __construct()
    {
        $this->initializeObjects();
    }    

   /**
     * Initialize each property using values retrieved from static configurations
     * 
     * @return void
     */
    private function initializeObjects(): void
    {
        $this->ajaxTemplate = $this->getObject( static::$initNamespace , "ajax");
        $this->chatBot = $this->getObject( static::$initNamespace , 'bot');
    }  

    /**
     * List of users messages.
     * Send users messages
     * Receive users messages
     *
     * @param string $html
     * @return void
     */
    public final function listOfMessages(string $html): void
    {

        static::rooter()->target(_DIR_ADMIN_TEMP_ . $html)->content([], true )->get();
    }

    /**
     * Start Epaphrodites Chatbot
     *
     * @param string $html
     * @return void
     */
    public final function startEpaphroditesChatBots(string $html): void
    {

        if (static::isValidMethod()) {

            $send = static::isAjax('__send__') ? static::isAjax('__send__') : '';

            $this->result = $this->chatBot->chatProcess($send);

            echo $this->ajaxTemplate->chatMessageContent($this->result);
           
            return;
        }
     
        static::rooter()->target(_DIR_ADMIN_TEMP_ . $html)->content([], true )->get();
    }    
}
