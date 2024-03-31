<?php

namespace Epaphrodites\epaphrodites\chatBot;

use Epaphrodites\epaphrodites\chatBot\modeleOne\loadSave\saveJsonDatas;
use Epaphrodites\epaphrodites\chatBot\modeleOne\loadSave\loadUsersAnswers;
use Epaphrodites\epaphrodites\chatBot\modeleOne\botConfig\botProcessConfig;

class processBotAnswers extends chatBot
{

    use saveJsonDatas, loadUsersAnswers, botProcessConfig;

    /**
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
}