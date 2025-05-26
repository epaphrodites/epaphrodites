<?php

namespace Epaphrodites\epaphrodites\api\Config;

class Requests {

    private const DEFAULT_HEADERS = [
        'Content-Type: application/json',
        'Accept: application/json',
        'User-Agent: Epaphrodites-API-Client/1.0'
    ];

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
    private static function request(
        string $path, 
        string $method = 'POST', 
        array $data = [], 
        $usersHeaders = [],
        bool $stream = false,
        ?callable $streamCallback = null
    ): array {

        $headers = [ ...$usersHeaders, ...self::DEFAULT_HEADERS ];

        if ($stream) {
            $data['stream'] = true;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, static::makePath($path));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, !$stream);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        
        if ($data && in_array($method, ['POST', 'PUT'])) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        if ($stream) {
            curl_setopt($ch, CURLOPT_WRITEFUNCTION, function($ch, $data) use ($streamCallback) {
                if ($streamCallback && is_callable($streamCallback)) {
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
     * API request with streaming support
     * 
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
     * Streaming without cache
     * 
     * @param string $path
     * @param array $data
     * @param bool $withBuffering
     * @return string
     */
    public static function streamChunks(
        string $path,
        array $data = [],
        bool $withBuffering = true
    ): string {
        $output = '';

        if ($withBuffering && !headers_sent()) {
            header('Content-Type: text/event-stream');
            header('Cache-Control: no-cache');
            header('Connection: keep-alive');
            header('X-Accel-Buffering: no');
        }

        if ($withBuffering) {
            while (@ob_end_flush());
            ob_implicit_flush(true);
            set_time_limit(0);
            flush();
        }

        $streamCallback = function($chunk) use (&$output, $withBuffering) {
            $escapedChunk = str_replace(["\n", "\r"], ['\\n', '\\r'], $chunk);
            $output .= $chunk . "\n";
            
            if ($withBuffering) {
                echo "$escapedChunk\n\n";
                flush();
                usleep(100000);
            }
        };

        $response = static::streamRequest(
            path: $path,
            data: $data,
            stream: true,
            onChunk: $streamCallback
        );

        if ($response['error']) {
            $output .= "Error: " . ($response['message'] ?? 'Unknown error') . "\n";
            if ($withBuffering) {
                echo "Error: " . ($response['message'] ?? 'Unknown error') . "\n\n";
                flush();
            }
        }

        if ($withBuffering) {
            flush();
        }

        return $output;
    }

    /**
     * API request with flexible method
     * 
     * @param string $path
     * @param string $method
     * @param array $data
     * @param mixed $usersHeaders
     * @param bool $stream
     * @param callable|null $onChunk
     * @return array{data: mixed, error: bool, status: array{error: bool, message: string|mixed}}
     */
    public static function get(
        string $path, 
        string $method = 'POST', 
        array $data = [], 
        $usersHeaders = [],
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
     * Build API URL
     * @param string $path
     * @return string
     */
    private static function makePath(
        string $path
    ): string {
        $apiUrl = '127.0.0.1:' . _PYTHON_SERVER_PORT_ . "{$path}";
        return $apiUrl;
    }
}
?>