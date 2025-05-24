<?php

namespace Epaphrodites\epaphrodites\api\Config;

class Requests {

    /**
     * API request
     * @param string $path
     * @param string $method
     * @param mixed $data
     * @param mixed $usersHeaders
     * @return array{data: mixed, error: bool, status: mixed|array{error: bool, message: string}}
     */
    public static function request(
        string $path, 
        string $method = 'GET', 
        $data = null, 
        $usersHeaders = []
    ):array {


        (array) $mainMeaders = [
            'Content-Type: application/json'
        ];

        $headers = [ ...$usersHeaders, ...$mainMeaders ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, static::makePath($path));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        
        if ($data && in_array($method, ['POST', 'PUT'])) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
        
        $response = curl_exec($ch);
        
        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            return ['error' => true, 'message' => $error];
        }
        
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        $decodedResponse = json_decode($response, true);
        
        return [
            'error' => $httpCode >= 400,
            'status' => $httpCode,
            'data' => $decodedResponse
        ];
    } 
    
    /**
     * Make path
     * @param string $path
     * @return string
     */
    private static function makePath(
        string $path
    ):string{

         $apiUrl = '127.0.0.1:'._PYTHON_SERVER_PORT_."{$path}";
         return $apiUrl;
    }

}