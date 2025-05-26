<?php

namespace Epaphrodites\epaphrodites\api\Config;

class Requests {

    /**
     * API request
     * @param string $path
     * @param string $method
     * @param mixed $data
     * @param mixed $usersHeaders
     * @param bool $stream
     * @param callable|null $streamCallback
     * @return array{data: mixed, error: bool, status: mixed|array{error: bool, message: string}}
     */
    public static function request(
        string $path, 
        string $method = 'POST', 
        array $data = [], 
        $usersHeaders = [],
        bool $stream = false,
        ?callable $streamCallback = null
    ):array {

        (array) $mainMeaders = [
            'Content-Type: application/json'
        ];

        $headers = [ ...$usersHeaders, ...$mainMeaders ];

        // Ajouter le paramètre stream aux données si nécessaire
        if ($stream) {
            $data['stream'] = true;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, static::makePath($path));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, !$stream); // false pour streaming
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        
        if ($data && in_array($method, ['POST', 'PUT'])) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        // Configuration pour le streaming
        if ($stream) {
            curl_setopt($ch, CURLOPT_WRITEFUNCTION, function($ch, $data) use ($streamCallback) {
                if ($streamCallback && is_callable($streamCallback)) {
                    // Traiter chaque chunk de données reçu
                    $streamCallback($data);
                }
                return strlen($data);
            });
        }
        
        $response = curl_exec($ch);
        
        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            return ['error' => true, 'message' => $error];
        }
        
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // Pour le streaming, la réponse est traitée via le callback
        if ($stream) {
            return [
                'error' => $httpCode >= 400,
                'status' => $httpCode,
                'data' => ['streaming' => true, 'completed' => true]
            ];
        }
        
        $decodedResponse = json_decode($response, true);
        
        return [
            'error' => $httpCode >= 400,
            'status' => $httpCode,
            'data' => $decodedResponse
        ];
    }

    /**
     * API request with streaming support - méthode simplifiée
     * @param string $path
     * @param array $data
     * @param bool $stream
     * @param callable|null $onChunk
     * @return array
     */
    public static function streamRequest(
        string $path,
        array $data = [],
        bool $stream = false,
        ?callable $onChunk = null
    ):array {
        
        return static::request(
            path: $path,
            method: 'POST',
            data: $data,
            usersHeaders: [],
            stream: $stream,
            streamCallback: $onChunk
        );
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