<?php

namespace Epaphrodites\epaphrodites\env\json\saveLoad;

use Epaphrodites\epaphrodites\ErrorsExceptions\epaphroditeException;

trait loadJson
{

    /**
     * Loads and retrieves data from a JSON file.
     * 
     * @return array|null Returns the decoded JSON data as an array or NULL if there's an issue.
     * @throws epaphroditeException If there's an error in file reading, JSON decoding, or the file is not found.
     */
    protected static function loadJsonFile(
        string $jsonFilePath
    ): ?array
    {
        
        // Check if the file exists
        if (file_exists($jsonFilePath)) {
            // Read the file content
            $jsonData = !empty(file_get_contents($jsonFilePath)) ? file_get_contents($jsonFilePath) : "[]";
           
            // Check if file reading is successful
            if ($jsonData !== false) {
                // Decode the JSON content
                $questionsAnswers = json_decode($jsonData, true);
             
                // Check if JSON decoding is successful and the result is an array
                if ($questionsAnswers !== null && is_array($questionsAnswers)) {
                    
                    return $questionsAnswers; // Return the decoded data
                } else {
                    // Handle an error if JSON decoding fails or the data type is not an array
                    throw new epaphroditeException("Error: Unable to decode the JSON file or the data type is not an array.");
                }
            } else {
                // Handle an error if file reading fails
                throw new epaphroditeException("Error: Unable to read the JSON file.");
            }
        } else {
            // Handle an error if the JSON file does not exist
            throw new epaphroditeException("Error: JSON file not found.");
        }
    }
}