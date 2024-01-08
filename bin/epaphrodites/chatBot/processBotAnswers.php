<?php

namespace Epaphrodites\epaphrodites\chatBot;

use Epaphrodites\epaphrodites\chatBot\loadSave\saveJsonDatas;
use Epaphrodites\epaphrodites\chatBot\loadSave\loadUsersAnswers;
use Epaphrodites\epaphrodites\chatBot\botConfig\botProcessConfig;

class processBotAnswers extends chatBot
{

    use saveJsonDatas, loadUsersAnswers, botProcessConfig;

    /**
     * @param string $userMessage
     * @return array
     */
    public final function chatProcess(string $userMessage): array
    {
        return $this->chatProcessConfig($userMessage);
    }

    /**
     * @param string $userMessage
     * @param string $botName
     * @return array
     */
    public final function herediaBot(string $userMessage , string $botName): array
    {
        
        return $this->herediaBotConfig($userMessage , $botName);
    }    
}
