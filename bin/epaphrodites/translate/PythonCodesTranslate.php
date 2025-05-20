<?php

declare(strict_types=1);

namespace Epaphrodites\epaphrodites\translate;

use Epaphrodites\epaphrodites\env\config\GeneralConfig;

class PythonCodesTranslate extends GeneralConfig
{
    
    /**
     * Execute python scripts
     * 
     * @param string|null $pyFunction
     * @param array $data
     * @param bool $useStreaming
     * @return mixed
     */
    public function executePython(
        string|null $pyFunction = null, 
        array $data = [],
        bool $useStreaming = false
    ): mixed {
        $getJsonContent = $this->loadJsonConfig();
     
        if (!empty($getJsonContent[$pyFunction])) {
            $scriptInfo = $getJsonContent[$pyFunction];
            $mergedDatas = array_merge(['function' => $scriptInfo["function"]], $data);
            
            $callback = $useStreaming ? function ($chunk) {
                echo $chunk;
                flush();
                ob_flush();
            } : null;
            
            return $this->pythonSystemCode(
                _PYTHON_FILE_FOLDERS_ . $scriptInfo["script"], 
                $mergedDatas,
                $callback,
                $useStreaming
            );
        } else {
            return false;
        }
    }

    /**
     * Sends an HTTP request to the specified endpoint using cURL.
     *
     * @param string $endpoint The API endpoint (e.g., '/api/resource')
     * @param array $options Configuration options for the request
     * @return array Response containing success status, data, HTTP status code, and optional error/info
     * @throws \InvalidArgumentException If invalid parameters are provided
     */
    public function pyApi(
        string $endpoint, 
        array $data = [], 
        string $method = 'GET', 
        array $options = []
    ): array{
        // Default configuration
        $defaultOptions = [
            'method' => $method,
            'timeout' => 10,
            'headers' => [],
            'data' => $data,
            'returnFullResponse' => false,
            'baseUrl' => 'http://127.0.0.1:' . (_PYTHON_SERVER_PORT_ ?? '5000'),
        ];

        // Validate and merge headers
        $defaultHeaders = ['Content-Type' => 'application/json'];
        $options['headers'] = array_merge($defaultHeaders, $options['headers'] ?? []);

        // Merge default options with provided options
        (array) $config = array_merge($defaultOptions, $options);

        // Validate method
        $allowedMethods = ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'];
        $config['method'] = strtoupper(string: $config['method']);
        if (!in_array($config['method'], $allowedMethods)) {
            throw new \InvalidArgumentException("Invalid HTTP method: {$config['method']}");
        }

        // Build the complete URL
        $url = rtrim($config['baseUrl'], '/') . '/' . ltrim($endpoint, '/');

        // Initialize cURL
        $ch = curl_init($url);
        if ($ch === false) {
            throw new \RuntimeException('Failed to initialize cURL');
        }

        // Configure cURL options
        $curlOptions = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => (int)$config['timeout'],
            CURLOPT_CONNECTTIMEOUT => (int)$config['timeout'],
            CURLOPT_CUSTOMREQUEST => $config['method'],
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_FAILONERROR => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HEADER => $config['returnFullResponse'],
        ];

        // Set headers
        if (!empty($config['headers'])) {
            $headers = [];
            foreach ($config['headers'] as $key => $value) {
                $headers[] = is_numeric($key) ? $value : "{$key}: {$value}";
            }
            $curlOptions[CURLOPT_HTTPHEADER] = $headers;
        }

        // Handle request data
        if ($config['data'] !== null && in_array($config['method'], ['POST', 'PUT', 'PATCH'])) {
            if (is_array($config['data']) && ($config['headers']['Content-Type'] ?? '') === 'application/json') {
                $curlOptions[CURLOPT_POSTFIELDS] = json_encode($config['data']);
            } elseif (is_array($config['data'])) {
                $curlOptions[CURLOPT_POSTFIELDS] = http_build_query($config['data']);
            } else {
                $curlOptions[CURLOPT_POSTFIELDS] = $config['data'];
            }
        }

        // Apply cURL options
        curl_setopt_array($ch, $curlOptions);

        // Execute request
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        $errorNo = curl_errno($ch);
        $responseInfo = $config['returnFullResponse'] ? curl_getinfo($ch) : null;

        // Close cURL session
        curl_close($ch);

        // Handle errors
        if ($errorNo) {
            return [
                'success' => false,
                'data' => null,
                'error' => "cURL Error ($errorNo): $error",
                'status' => 0,
                'info' => $responseInfo,
            ];
        }

        // Attempt to decode JSON response if applicable
        $data = $response;
        if (($config['headers']['Content-Type'] ?? '') === 'application/json' && is_string($response)) {
            $decoded = json_decode($response, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $data = $decoded;
            }
        }

        return [
            'success' => ($httpCode >= 200 && $httpCode < 300),
            'data' => $data,
            'status' => $httpCode,
            'info' => $responseInfo,
        ];
    }

    /**
     * Get JSON content from the config file.
     * @return array
     */
    private function loadJsonConfig(): array
    {
        $getFiles = _PYTHON_FILE_FOLDERS_ . 'config/config.json';

        return json_decode(file_get_contents($getFiles), true);
    }
}