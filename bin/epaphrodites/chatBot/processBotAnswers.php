<?php

namespace Epaphrodites\epaphrodites\chatBot;

use Epaphrodites\epaphrodites\auth\session_auth;

class processBotAnswers extends chatBot
{

    use saveJsonDatas, loadUsersAnswers;

    /**
     * @param string $userMessage
     * @return array
     */
    public final function chatProcess(string $userMessage): array
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
}
