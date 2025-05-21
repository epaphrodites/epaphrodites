<?php

namespace Epaphrodites\controllers\render\Http;

use RuntimeException;

class HttpClient extends HttpRequest
{

    private const TIMEOUT_SECONDS = 15;
    private const SLEEP_MICROSECONDS = 200_000;

    /**
     * @return mixed
     */
    public function HttpResponses():mixed
    {

        $getUrl = static::class('paths')->href_slug($this->ParseMethod());

        $cleanUrl = preg_replace('#/+#', '/', $getUrl);

        return rtrim($cleanUrl, '/') . '/';
    }

    /**
     * @return mixed
     */
    private function ParseMethod(): mixed
    {
        $httpRequest = $this->HttpRequest();
        
        return (!empty($httpRequest) && $httpRequest !== "/" && strlen($httpRequest) > 1 && $httpRequest[-1] === "/")
            ? substr($httpRequest, 1)
            : _DASHBOARD_;
    }
    
    /**
     * @return void
     */
    public function runPythonServer():void{

        _RUN_PYTHON_SERVER_ == true ? $this->startServer() : NULL;
    }

    /**
     * Starts the Python server in the background, with support for streaming APIs.
     *
     * @throws RuntimeException
     * @return void
     */
    public function startServer(): void
    {
        // Skip if server execution is disabled
        if (!defined('_RUN_PYTHON_SERVER_') || !_RUN_PYTHON_SERVER_) {
            return;
        }

        // Check if server is already running
        if ($this->isRunning()) {
            return;
        }

        // Validate configuration
        $this->validateConfig();

        // Prepare command components
        $python = escapeshellcmd(_PYTHON_ ?? 'python3');
        $port = escapeshellarg((string)(_PYTHON_SERVER_PORT_ ?? '5001'));
        $filePath = escapeshellarg(_PYTHON_FILE_FOLDERS_ . 'config/server.py');
        $logFile = escapeshellarg('pythonServer.log');

        $isWindows = PHP_OS_FAMILY === 'Windows';

        // Build command
        $command = $isWindows
            ? "start /B $python $filePath --port $port > $logFile 2>&1"
            : "bash -c " . escapeshellarg("nohup $python $filePath --port $port >> $logFile 2>&1 &");

        // Define descriptors for process
        $descriptorspec = [
            0 => ['pipe', 'r'], // stdin
            1 => ['file', 'pythonServer.log', 'a'], // stdout
            2 => ['file', 'pythonServer.log', 'a'], // stderr
        ];

        // Start the process
        $process = proc_open($command, $descriptorspec, $pipes);

        if (!is_resource($process)) {
            throw new RuntimeException("Failed to start the Python server process.");
        }

        // Close pipes
        foreach ($pipes as $pipe) {
            if (is_resource($pipe)) {
                fclose($pipe);
            }
        }

        // Get process status
        $status = proc_get_status($process);
        proc_close($process);

        // Check if process started successfully
        if (!$status['running'] && $status['exitcode'] !== -1) {
            $logContent = file_exists('pythonServer.log') ? substr(file_get_contents('pythonServer.log'), -500) : 'No log available';
            throw new RuntimeException("Python server process exited with code {$status['exitcode']}. Log: $logContent");
        }

        // Wait for server to become available
        $start = time();
        while (time() - $start < self::TIMEOUT_SECONDS) {
            if ($this->isRunning()) {
                return;
            }
            usleep(self::SLEEP_MICROSECONDS);
        }

        $logContent = file_exists('pythonServer.log') ? substr(file_get_contents('pythonServer.log'), -500) : 'No log available';
        throw new RuntimeException("The Python server could not be started within " . self::TIMEOUT_SECONDS . " seconds. Log: $logContent");
    }

    /**
     * Checks if the Python server is running on the specified port using a TCP connection.
     *
     * @return bool
     */
    private function isRunning(): bool
    {
        $port = (int)(_PYTHON_SERVER_PORT_ ?? 5001);
        $host = '127.0.0.1';

        // Try a TCP connection
        $fp = @fsockopen($host, $port, $errno, $errstr, 1.0);
        if ($fp) {
            fclose($fp);
            return true;
        }

        return false;
    }

    /**
     * Validates required configuration constants.
     *
     * @throws RuntimeException
     */
    private function validateConfig(): void
    {
        if (!defined('_PYTHON_') || empty(_PYTHON_)) {
            throw new RuntimeException('Python executable path (_PYTHON_) is not defined.');
        }
        if (!defined('_PYTHON_SERVER_PORT_') || !is_numeric(_PYTHON_SERVER_PORT_) || _PYTHON_SERVER_PORT_ <= 0 || _PYTHON_SERVER_PORT_ > 65535) {
            throw new RuntimeException('Invalid or undefined server port (_PYTHON_SERVER_PORT_).');
        }
        if (!defined('_PYTHON_FILE_FOLDERS_') || !file_exists(_PYTHON_FILE_FOLDERS_ . 'config/server.py')) {
            throw new RuntimeException('Python server file does not exist (_PYTHON_FILE_FOLDERS_/config/server.py).');
        }
    }
}