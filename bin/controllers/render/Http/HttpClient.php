<?php

namespace Epaphrodites\controllers\render\Http;

class HttpClient extends HttpRequest
{

    private const TIMEOUT_SECONDS = 5;
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
     * @throws \RuntimeException
     * @return void
     */
    public function startServer(): void{

        if (!defined('_RUN_PYTHON_SERVER_') || !_RUN_PYTHON_SERVER_) {
            return;
        }

        if ($this->isRunning()) {
            return;
        }

        $python = escapeshellcmd((string) (_PYTHON_ ?? 'python3'));
        $port = "--port ". escapeshellarg((string) (_PYTHON_SERVER_PORT_ ?? '5000'));
        $filePath = escapeshellarg((string) (_PYTHON_FILE_FOLDERS_ . 'config/server.py'));
        $logFile = "pythonServer.log";

        $isWindows = strtoupper(substr(PHP_OS_FAMILY, 0, 3)) === 'WIN';

        $command = $isWindows
            ? "start /B $python $filePath $port > $logFile 2>&1"
            : "bash -c " . escapeshellarg("nohup $python $filePath $port >> $logFile 2>&1 &");

        $descriptorspec = [
            0 => ['pipe', 'r'],
            1 => ['file', $logFile, 'a'],
            2 => ['file', $logFile, 'a'],
        ];

        $process = proc_open($command, $descriptorspec, $pipes);

        if (is_resource($process)) {
            foreach ($pipes as $pipe) {
                if (is_resource($pipe)) {
                    fclose($pipe);
                }
            }
            proc_close($process);
        }

        $start = time();
        while (time() - $start < self::TIMEOUT_SECONDS) {
            if ($this->isRunning()) {
                return;
            }
            usleep(self::SLEEP_MICROSECONDS);
        }

        throw new \RuntimeException("The Python server could not be started in time.");
    }

    /**
     * @return bool
     */
    private function isRunning(): bool
    {
        $port = (int) (_PYTHON_SERVER_PORT_ ?? 5000);

        $fp = @fsockopen('127.0.0.1', $port, $errno, $errstr, 1.0);

        if ($fp) {
            fclose($fp);
            return true;
        }

        return false;
    }

}