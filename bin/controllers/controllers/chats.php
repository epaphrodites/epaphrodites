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
     * This chatbot requires that php be installed
     * Start Epaphrodites Chatbot one
     *
     * @param string $html
     * @return void
     */
    public final function startChatbotModelOne(
        string $html
    ): void
    {

        if (static::isValidMethod(true)) {

            $send = static::isAjax('__send__') ? static::isAjax('__send__') : '';

            $this->result = $this->chatBot->chatBotmodelOneProcess($send);

            echo $this->ajaxTemplate->chatMessageContent($this->result , $send);
           
            return;
        }
     
        $this->views( $html, [], true );
    }  
    
    /**
     * This chatbot requires that Python be installed
     * Start Epaphrodites Chatbot two
     * @param string $html
     * @return void
     */
    public final function startChatbotModelTwo(
        string $html
    ): void
    {

        if (static::isValidMethod(true)) {
            
            $send = static::isAjax('__send__') ? static::isAjax('__send__') : '';

            $this->result = $this->chatBot->chatBotModelTwoProcess($send);

            echo $this->ajaxTemplate->chatMessageContent($this->result , $send);
           
            return;
        }
     
        $this->views( $html, [], true );
    }   

    /**
    * Start Epaphrodites recognition
    * @param string $html
    * @return void
    */
    public final function startBotWriting(
        string $html
    ): void{      

        $this->views( $html, [], true );
    }    
}