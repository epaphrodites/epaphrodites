<?php

declare(strict_types=1);

namespace Epaphrodites\epaphrodites\translate;

use Epaphrodites\epaphrodites\env\config\GeneralConfig;
use InvalidArgumentException;
use RuntimeException;

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
     * Makes an HTTP request to the Python API with support for streaming and dynamic responses.
     *
     * @param string $endpoint The API endpoint (e.g., '/chat/completions')
     * @param array $data Request payload (for POST, PUT, PATCH)
     * @param string $method HTTP method (GET, POST, PUT, DELETE, PATCH)
     * @param array $options Additional options (headers, timeout, streamCallback, etc.)
     * @return array|string Response data or streamed output
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function pyApi(
        string $endpoint,
        array $data = [],
        string $method = 'GET',
        array $options = []
    ): array|string {
        // Default configuration
        $defaultOptions = [
            'method' => $method,
            'timeout' => 30, // Increased timeout for streaming
            'headers' => [],
            'data' => $data,
            'returnFullResponse' => false,
            'baseUrl' => 'http://127.0.0.1:' . (_PYTHON_SERVER_PORT_ ?? '5001'),
            'stream' => false, // Enable streaming mode
            'streamCallback' => null, // Callback for streaming data
            'responseType' => 'json', // Expected response type: 'json', 'text', 'stream'
        ];

        // Validate and merge headers
        $defaultHeaders = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
        $options['headers'] = array_merge($defaultHeaders, $options['headers'] ?? []);

        // Merge default options with provided options
        $config = array_merge($defaultOptions, $options);

        // Validate method
        $allowedMethods = ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'];
        $config['method'] = strtoupper($config['method']);
        if (!in_array($config['method'], $allowedMethods)) {
            throw new InvalidArgumentException("Invalid HTTP method: {$config['method']}");
        }

        // Validate streaming configuration
        if ($config['stream'] && !is_callable($config['streamCallback'])) {
            throw new InvalidArgumentException("Stream mode requires a valid callback function");
        }

        // Build the complete URL
        $url = rtrim($config['baseUrl'], '/') . '/' . ltrim($endpoint, '/');

        // Initialize cURL
        $ch = curl_init($url);
        if ($ch === false) {
            throw new RuntimeException('Failed to initialize cURL');
        }

        // Configure cURL options
        $curlOptions = [
            CURLOPT_RETURNTRANSFER => !$config['stream'], // Disable for streaming
            CURLOPT_TIMEOUT => (int)$config['timeout'],
            CURLOPT_CONNECTTIMEOUT => (int)$config['timeout'],
            CURLOPT_CUSTOMREQUEST => $config['method'],
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_FAILONERROR => false,
            CURLOPT_SSL_VERIFYPEER => false, // Consider enabling in production with proper certificates
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

        // Handle streaming for SSE or chunked responses
        if ($config['stream']) {
            $curlOptions[CURLOPT_WRITEFUNCTION] = function ($ch, $data) use ($config) {
                $callback = $config['streamCallback'];
                if ($config['responseType'] === 'stream') {
                    // Handle SSE or chunked JSON
                    $lines = explode("\n", $data);
                    foreach ($lines as $line) {
                        $line = trim($line);
                        if (empty($line)) {
                            continue;
                        }
                        // Parse SSE format (e.g., "data: {...}")
                        if (strpos($line, 'data:') === 0) {
                            $jsonData = substr($line, 5); // Remove "data:"
                            $decoded = json_decode($jsonData, true);
                            if (json_last_error() === JSON_ERROR_NONE) {
                                call_user_func($callback, $decoded);
                            } else {
                                call_user_func($callback, ['raw' => $line]);
                            }
                        } else {
                            // Handle raw streaming data
                            call_user_func($callback, ['raw' => $line]);
                        }
                    }
                } else {
                    // Handle raw streaming data
                    call_user_func($callback, ['raw' => $data]);
                }
                return strlen($data);
            };
            $curlOptions[CURLOPT_BUFFERSIZE] = 128; // Small buffer for streaming
        }

        // Handle request data
        if ($config['data'] !== [] && in_array($config['method'], ['POST', 'PUT', 'PATCH'])) {
            if ($config['headers']['Content-Type'] === 'application/json') {
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
        if ($config['stream']) {
            // Streaming mode: execute and process via callback
            $result = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            $errorNo = curl_errno($ch);
            $responseInfo = $config['returnFullResponse'] ? curl_getinfo($ch) : null;

            curl_close($ch);

            if ($errorNo) {
                throw new RuntimeException("cURL Error ($errorNo): $error");
            }

            return [
                'success' => ($httpCode >= 200 && $httpCode < 300),
                'status' => $httpCode,
                'info' => $responseInfo,
            ];
        }

        // Non-streaming mode
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        $errorNo = curl_errno($ch);
        $responseInfo = $config['returnFullResponse'] ? curl_getinfo($ch) : null;

        curl_close($ch);

        // Handle errors
        if ($errorNo) {
            return [
                'success' => false,
                'data' => null,
                'error' => "cURL Error ($errorNo): $error",
                'status' => $httpCode,
                'info' => $responseInfo,
            ];
        }

        // Process response based on responseType
        $data = $response;
        if ($config['responseType'] === 'json' && is_string($response)) {
            $decoded = json_decode($response, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $data = $decoded;
            }
        } elseif ($config['responseType'] === 'text') {
            $data = (string)$response;
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