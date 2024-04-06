<?php

namespace Epaphrodites\epaphrodites\chatBot;

use Epaphrodites\epaphrodites\chatBot\modeleOne\loadSave\saveJsonDatas;
use Epaphrodites\epaphrodites\chatBot\modeleOne\loadSave\loadUsersAnswers;
use Epaphrodites\epaphrodites\chatBot\modeleOne\botConfig\botProcessConfig;

class processBotAnswers extends chatBot
{

    use saveJsonDatas, loadUsersAnswers, botProcessConfig;

    /**
     * Chat bot model one init
     * @param string $userMessage
     * @return array
    */
    public final function chatBotModeleOneProcess(
        string $userMessage
    ): array
    {
        return $this->chatProcessConfig($userMessage);
    }

    /**
     * Chatbot model one customizable
     * @param string $userMessage
     * @param string $botName
     * @return array
    */
    public final function herediaBotModeleOne(
        string $userMessage, 
        string $botName
    ): array
    {
        return $this->herediaBotConfig($userMessage , $botName);
    }      

    /**
     * Chatbot model two customizable
     * @param string $userMessage
     * @param bool $learn
     * @return array
    */
    public final function chatBotModeleTwoProcess(
        string $userMessage, 
        bool $learn = true
    ): array
    {
        return $this->herediaChatBotProcessConfig($userMessage , $learn);
    }  
    
    /**
     * Chatbot model three
     * @param string $userMessage
     * @return array
    */
    public final function chatBotModeleThreeProcess(
        string $userMessage
    ): array
    {
        return $this->noellaChatBotProcessConfig($userMessage);
    }      
}