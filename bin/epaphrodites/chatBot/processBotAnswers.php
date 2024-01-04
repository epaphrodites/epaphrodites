<?php

namespace Epaphrodites\epaphrodites\chatBot;

class processBotAnswers extends Chatbot {

    use saveJsonDatas, loadUsersAnswers;

    /**
     * @param string $userMessage
     * @return array
     */
    public final function chatProcess(string $userMessage ):array
    {
        // Find and store the response for the user message
        $response = $this->findResponse($userMessage);
    
        // Add the new response to existing data
        $existingData[] = $response;
    
        // Save the updated data to the JSON file
        $this->saveJson($existingData);

        // Load existing JSON data, if any
        $existingData = $this->loadJsonFile();        
    
        // Return the updated data including the new response
        return $existingData;
    }
}