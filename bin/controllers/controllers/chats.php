<?php

namespace Epaphrodites\controllers\controllers;

use Epaphrodites\controllers\switchers\MainSwitchers;

final class chats extends MainSwitchers
{
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
    public final function listOfMessages(
        string $html
    ): void
    {

        $this->views( $html, [], true );
    }

    /**
     * Start Epaphrodites Chatbot
     *
     * @param string $html
     * @return void
     */
    public final function startEpaphroditesChatBots(
        string $html
    ): void
    {

        if (static::isValidMethod(true)) {

            $send = static::isAjax('__send__') ? static::isAjax('__send__') : '';

            $this->result = $this->chatBot->chatBotModeleOneProcess($send);

            echo $this->ajaxTemplate->chatMessageContent($this->result , $send);
           
            return;
        }
     
        $this->views( $html, [], true );
    }  
    
    /**
     * This chatbot requires that Python be installed
     * Start Heredia Chatbot
     * @param string $html
     * @return void
     */
    public final function startHerediaBot(
        string $html
    ): void
    {

        if (static::isValidMethod(true)) {
            
            $send = static::isAjax('__send__') ? static::isAjax('__send__') : '';

            $this->result = $this->chatBot->chatBotModeleTwoProcess($send);

            echo $this->ajaxTemplate->chatMessageContent($this->result , $send);
           
            return;
        }
     
        $this->views( $html, [], true );
    }     
}