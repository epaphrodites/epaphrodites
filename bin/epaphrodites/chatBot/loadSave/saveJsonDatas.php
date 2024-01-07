<?php

namespace Epaphrodites\epaphrodites\chatBot\loadSave;

use Epaphrodites\epaphrodites\ErrorsExceptions\epaphroditeException;

trait saveJsonDatas
{
    
    /**
     * save JSON file.
     * 
     * @return bool|null Returns the decoded JSON data as an bool or NULL if there's an issue.
     * @throws epaphroditeException If there's an error in file reading, JSON decoding, or the file is not found.
     */
    private function saveJson(array $datas , string $jsonFiles = 'BotSession'):bool {

        // Path to the JSON file
        $jsonFilePath = _DIR_JSON_DATAS_ . "/user{$jsonFiles}.json";

        $JsonDatas = file_get_contents($jsonFilePath);

        // Convert data to JSON format
        $jsonData = json_decode($JsonDatas);

        $jsonData = array_merge($jsonData , $datas);
    
        // Check for JSON encoding errors
        if ($jsonData === false) {
            throw new epaphroditeException('Error encoding JSON');
        }
       
        // Write data to the file
        $bytesWritten = file_put_contents($jsonFilePath, json_encode($jsonData,JSON_PRETTY_PRINT));
    
        // Check for file writing errors
        if ($bytesWritten === false) {
            throw new epaphroditeException('Failed to write to JSON file');
        }

        return true;
    }
}