<?php

namespace Epaphrodites\epaphrodites\api\Config;

class Requests
{
    /**
     * Default headers for API requests
     */
    private const DEFAULT_HEADERS = [
        'Content-Type: application/json',
        'Accept: application/json',
        'User-Agent: Epaphrodites-API-Client/1.0'
    ];

    /**
     * API request
     *
     * @param string $path
     * @param string $method
     * @param array $data
     * @param array $usersHeaders
     * @param bool $stream
     * @param callable|null $streamCallback
     * @return array{data: mixed, error: bool, status: int|array{error: bool, message: string}}
     */
    private static function request(
        string $path,
        string $method = 'POST',
        array $data = [],
        array $usersHeaders = [], // Type corrigé de mixed à array
        bool $stream = false,
        ?callable $streamCallback = null
    ): array {
        // Fusion des en-têtes avec priorité aux en-têtes utilisateur
        $headers = array_merge(self::DEFAULT_HEADERS, $usersHeaders);

        // Initialisation de cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, static::makePath($path));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, !$stream);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method)); // Normalisation de la méthode
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // À considérer : activer en production avec certificats valides

        // Ajout des en-têtes si non vides
        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        // Gestion des données pour POST/PUT
        if (!empty($data) && in_array(strtoupper($method), ['POST', 'PUT'])) {
            $jsonData = json_encode($data);
            if ($jsonData === false) {
                return [
                    'error' => true,
                    'status' => 0,
                    'message' => 'Failed to encode data to JSON'
                ];
            }
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        }

        // Gestion du streaming
        if ($stream) {
            curl_setopt($ch, CURLOPT_WRITEFUNCTION, function ($ch, $data) use ($streamCallback) {
                if ($streamCallback && is_callable($streamCallback)) {
                    call_user_func($streamCallback, $data);
                }
                return strlen($data);
            });
        }

        // Exécution de la requête
        $response = curl_exec($ch);

        // Gestion des erreurs cURL
        if ($response === false) {
            $error = curl_error($ch);
            $errno = curl_errno($ch);
            curl_close($ch);
            return [
                'error' => true,
                'status' => 0,
                'message' => "cURL error ($errno): $error"
            ];
        }

        // Récupération du code HTTP
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // Gestion de la réponse pour le streaming
        if ($stream) {
            return [
                'error' => $httpCode >= 400,
                'status' => $httpCode,
                'data' => ['streaming' => true, 'completed' => true]
            ];
        }

        // Décodage de la réponse JSON
        $decodedResponse = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return [
                'error' => true,
                'status' => $httpCode,
                'message' => 'Failed to decode JSON response: ' . json_last_error_msg()
            ];
        }

        return [
            'error' => $httpCode >= 400,
            'status' => $httpCode,
            'data' => $decodedResponse ?? []
        ];
    }

    /**
     * API request with streaming support
     *
     * @param string $path
     * @param array $data
     * @param bool $stream
     * @param callable|null $onChunk
     * @return array
     */
    public static function stream(
        string $path,
        array $data = [],
        bool $stream = false,
        ?callable $onChunk = null
    ): array {
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
     * Generic API request
     *
     * @param string $path
     * @param string $method
     * @param array $data
     * @param array $usersHeaders
     * @param bool $stream
     * @param callable|null $onChunk
     * @return array{data: mixed, error: bool, status: int|array{error: bool, message: string}}
     */
    public static function get(
        string $path,
        string $method = 'POST',
        array $data = [],
        array $usersHeaders = [],
        bool $stream = false,
        ?callable $onChunk = null
    ): array {
        return static::request(
            path: $path,
            method: $method,
            data: $data,
            usersHeaders: $usersHeaders,
            stream: $stream,
            streamCallback: $onChunk
        );
    }

    /**
     * Build API path
     *
     * @param string $path
     * @return string
     * @throws \RuntimeException if _PYTHON_SERVER_PORT_ is not defined
     */
    private static function makePath(
        string $path
    ): string {
        if (!defined('_PYTHON_SERVER_PORT_')) {
            throw new \RuntimeException('Python server port is not defined');
        }

        // Nettoyage du chemin
        $path = ltrim($path, '/');
        return "http://127.0.0.1:" . _PYTHON_SERVER_PORT_ . "/{$path}";
    }
}