<?php

namespace Epaphrodites\epaphrodites\chatBot;

use Epaphrodites\epaphrodites\ErrorsExceptions\epaphroditeException;

trait saveJsonDatas
{
    
    /**
     * save JSON file.
     * 
     * @return bool|null Returns the decoded JSON data as an bool or NULL if there's an issue.
     * @throws epaphroditeException If there's an error in file reading, JSON decoding, or the file is not found.
     */
    private function saveJson(array $datas):bool {

        // Path to the JSON file
        $jsonFilePath = _DIR_JSON_DATAS_ . '/userBotSession.json';
    
        // Convert data to JSON format
        $jsonData = json_encode($datas, JSON_PRETTY_PRINT);
    
        // Check for JSON encoding errors
        if ($jsonData === false) {
            throw new epaphroditeException('Error encoding JSON');
        }
    
        // Write data to the file
        $bytesWritten = file_put_contents($jsonFilePath, $jsonData, LOCK_EX);
    
        // Check for file writing errors
        if ($bytesWritten === false) {
            throw new epaphroditeException('Failed to write to JSON file');
        }

        return true;
    }
}