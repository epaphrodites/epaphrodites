<?php

namespace Epaphrodites\epaphrodites\chatBot;

use Epaphrodites\epaphrodites\auth\session_auth;

trait botProcessConfig
{

  /**
     * @param string $userMessage
     * @return array
     */
    private final function chatProcessConfig(string $userMessage): array
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
     * @return array
     */
    private final function herediaBotConfig(string $userMessage , string $botName): array
    {
        $result =[];
        
        if (!empty($userMessage)) {

            // Find and store the response for the user message
            $response = $this->findHerediaResponse($userMessage , $botName);

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

}