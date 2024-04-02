<?php

namespace Epaphrodites\epaphrodites\chatBot\modeleOne\botConfig;

use Epaphrodites\epaphrodites\auth\session_auth;
use Epaphrodites\epaphrodites\define\config\traits\currentFunctionNamespaces;

trait botProcessConfig
{

    use currentFunctionNamespaces;

  /**
     * @param string $userMessage
     * @return array
     */
    private function chatProcessConfig(
        string $userMessage
    ): array
    {
        $result =[];
        
        if (!empty($userMessage)) {

            // Find and store the response for the user message
            $response = $this->findResponse($userMessage);

            // Add the new response to existing data
            $existingData[] = $response;

            // Save the updated data to the JSON file
            $this->saveJson($existingData);
        }

        $login = (new session_auth)->login();

        // Load existing JSON data, if any
        $existingData = $this->loadJsonFile();

        foreach ($existingData as $key => $value) {
            if ($value['login'] === $login) {
                $result[] = $existingData[$key];
            }
        }

        // Return the updated data including the new response
        return $result;
    }

    /**
     * @param string $userMessage
     * @param string $botName
     * @return array
     */
    private function herediaBotConfig(
        string $userMessage, 
        string $botName
    ): array
    {

        $result =[];
        
        if (!empty($userMessage)) {

            // Find and store the response for the user message
            $response = $this->findHerediaResponse($userMessage , $botName);
            
            // Add the new response to existing data
            $existingData[] = $response;

            // Save the updated data to the JSON file
            $this->saveJson($existingData , $botName);
        }

        $login = (new session_auth)->login();

        // Load existing JSON data, if any
        $existingData = $this->loadJsonFile($botName);
        
        foreach ($existingData as $key => $value) {
            if ($value['login'] === $login) {
                $result[] = $existingData[$key];
            }
        }

        // Return the updated data including the new response
        return $result;
    }

   /**
     * @param string $userMessage
     * @param bool $learn
     * @return array
     */
    private function herediaChatBotProcessConfig(
        string $userMessage, 
        bool $learn
        ):array
    {
        $result =[];
        
        $login = (new session_auth)->login();

        if (!empty($userMessage)) {

            static::initConfig()['python']->executePython('lunchBotModelTwo',[ 'login' => $login, 'learn'=>$learn, 'userMessages'=>$userMessage ]);
        }

        // Load existing JSON data, if any
        $existingData = $this->loadOthersJsonFile('hippocampusModelTwo');

        foreach ($existingData as $key => $value) {
            if ($value['login'] === $login) {
                $result[] = $existingData[$key];
            }
        }

        return $result;
    }    
}