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
     * Automatically starts the Python server if enabled
     */
    public function autoStarted(): void
    {
        $port = _PYTHON_SERVER_PORT_;
        _RUN_PYTHON_SERVER_ == true && $this->isPythonServerRunning( $port) == false ? $this->startServer() : null;
    }

    /**
     * Checks if the Python server is running
     * 
     * @param int $port Server port
     * @param string $host Server IP address
     * @return bool True if the server responds, false otherwise
     */
    protected function isPythonServerRunning($port, $host = '127.0.0.1') 
    {
        $connection = @fsockopen($host, $port, $errno, $errstr, 1);
        if ($connection) {
            fclose($connection);
            return true;
        }
        
        return false;
    }  

    /**
     * Method to start the Python server
     */
    public function startServer()
    {
        $port = _PYTHON_SERVER_PORT_;
        $host = '127.0.0.1';
        $filePath = _PYTHON_FILE_FOLDERS_ . 'config/server.py';

        // Check if the server is already running
        if ($this->isPythonServerRunning($port, $host)) {
            return;
        }

        // Launch the server
        $result = $this->executePythonServer($filePath, $port, $host, true);

        $attempts = 0;
        $maxAttempts = 10;

        while ($attempts < $maxAttempts) {
            sleep(1);
            if ($this->isPythonServerRunning($port, $host)) {
                return;
            }
            $attempts++;
        }

        if ($result['pid']) {
            $this->stopPythonServer($result['pid']);
        }

        return;
    }  

    /**
     * Executes the Python server.py in the context of a Symfony command
     * 
     * @param string $scriptPath Path to the server.py file
     * @param int $port Server port
     * @param string $host Server IP address (default 127.0.0.1)
     * @param bool $background Run in background (default true)
     * @return array Execution result
     */
    protected function executePythonServer($scriptPath, $port, $host = '127.0.0.1', $background = true) 
    {
        if (!file_exists($scriptPath)) {
            $error = "The file $scriptPath does not exist";
            return ['success' => false, 'error' => $error, 'output' => null, 'pid' => null];
        }

        $command = "python " . escapeshellarg($scriptPath) . " --host=" . escapeshellarg($host) . " --port=" . escapeshellarg($port);

        if ($background) {
            if (PHP_OS_FAMILY === 'Windows') {
                // Windows: Run in background and retrieve PID
                $command = "start /B " . $command . " > nul 2>&1";
                $pidCommand = "wmic process where \"CommandLine like '%" . basename($scriptPath) . "%' and Name='python.exe'\" get ProcessId";
            } else {
                // Linux/Unix/Mac: Run in background and retrieve PID
                $command = $command . " > /dev/null 2>&1 & echo $!";
            }
        }

        $output_array = [];
        $returnCode = 0;

        if ($background && PHP_OS_FAMILY === 'Windows') {
            // Execute command in background
            exec($command, $output_array, $returnCode);
            // Wait briefly for the process to start
            sleep(1);
            // Retrieve PID
            exec($pidCommand, $pidOutput);
            $pid = null;
            foreach ($pidOutput as $line) {
                if (is_numeric(trim($line))) {
                    $pid = (int) trim($line);
                    break;
                }
            }
            $result = [
                'success' => $returnCode === 0,
                'error' => $returnCode !== 0 ? "Error during launch (code: $returnCode)" : null,
                'output' => $output_array,
                'pid' => $pid,
                'background' => true
            ];
        } else {
            exec($command, $output_array, $returnCode);
            $pid = PHP_OS_FAMILY !== 'Windows' && $background ? (int) end($output_array) : null;
            $result = [
                'success' => $returnCode === 0,
                'error' => $returnCode !== 0 ? "Error during execution (code: $returnCode)" : null,
                'output' => $output_array,
                'pid' => $pid,
                'background' => $background
            ];
        }

        return $result;
    }    

    /**
     * Stops a Python process by its PID
     * 
     * @param int $pid Process PID
     * @return bool True if the process was stopped, false otherwise
     */
    protected function stopPythonServer($pid) 
    {
        if (!$pid) {
            return false;
        }

        if (PHP_OS_FAMILY === 'Windows') {
            $command = "taskkill /F /PID " . escapeshellarg($pid);
        } else {
            $command = "kill " . escapeshellarg($pid);
        }

        $output_array = [];
        $returnCode = 0;
        exec($command, $output_array, $returnCode);
        
        $success = $returnCode === 0;
        
        return $success;
    }    
}