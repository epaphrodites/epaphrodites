<?php

namespace Epaphrodites\epaphrodites\Console\Models;
        
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Epaphrodites\epaphrodites\Console\Setting\settingreloadPythonServer;
        
class modelreloadPythonServer extends settingreloadPythonServer{
       
    private const TIMEOUT_SECONDS = 5;
    private const SLEEP_MICROSECONDS = 200_000;    
        
    /**
    * @param \Symfony\Component\Console\Input\InputInterface $input
    * @param \Symfony\Component\Console\Output\OutputInterface $output
    */
    protected function execute( InputInterface $input, OutputInterface $output)
    {
        $this->killPythonPort(_PYTHON_SERVER_PORT_);

        $this->startServer();

        $output->writeln("<info>The server has been relead successfuly!!!✅</info>");
        return static::SUCCESS;  
    }

    /**
     * Stops all processes listening on a specified TCP port.
     *
     * @param int $port The TCP port whose processes should be terminated
     * @param bool $force If true, forces process termination (SIGKILL on Unix, /F on Windows)
     * @param bool $silent If true, suppresses error messages
     * @return array{success: bool, killed: int, errors: array} Operation result
     */
    private function killPythonPort(
        int $port,
        bool $force = true,
        bool $silent = false
    ): array {
        if ($port <= 0 || $port > 65535) {
            return [
                'success' => false,
                'killed' => 0,
                'errors' => ['Invalid port. Must be between 1 and 65535']
            ];
        }
        $errors = [];
        $pids = [];
        
        try {
            if (PHP_OS_FAMILY === 'Windows') {
                $command = "netstat -ano | findstr :{$port}";
                exec($command, $lines, $exitCode);
                
                if ($exitCode !== 0 && !$silent) {
                    $errors[] = "Error executing netstat command (code: $exitCode)";
                }
                
                foreach ($lines as $line) {
                    if (preg_match('/(?:TCP|UDP).+?:\d+\s+(?:\S+\s+)*?(\d+)/i', $line, $m)) {
                        $pids[] = $m[1];
                    }
                }
                
                foreach (array_unique($pids) as $pid) {
                    if ($pid <= 4 || $pid == getmypid()) {
                        $errors[] = "Skipping PID $pid (system process or current process)";
                        continue;
                    }
                    
                    $killCommand = "taskkill /PID $pid" . ($force ? " /F" : "");
                    exec($killCommand, $output, $killExitCode);
                    
                    if ($killExitCode !== 0 && !$silent) {
                        $errors[] = "Failed to terminate process $pid (code: $killExitCode)";
                    }
                }
            } else {
                $command = "lsof -i tcp:{$port} -t 2>/dev/null";
                exec($command, $pids, $exitCode);
                
                if ($exitCode !== 0 && $exitCode !== 1 && !$silent) {
                    $errors[] = "Error executing lsof command (code: $exitCode)";
                }
                
                $pids = array_filter(array_map('trim', $pids), function($pid) {
                    return is_numeric($pid) && $pid > 0 && $pid != getmypid();
                });
                
                foreach ($pids as $pid) {
                    $killCommand = "kill " . ($force ? "-9 " : "") . escapeshellarg($pid) . " 2>/dev/null";
                    exec($killCommand, $output, $killExitCode);
                    
                    if ($killExitCode !== 0 && !$silent) {
                        $errors[] = "Failed to terminate process $pid (code: $killExitCode)";
                    }
                }
            }
            
            $killedCount = count(array_unique($pids));
            
            return [
                'success' => ($killedCount > 0 && count($errors) === 0),
                'killed' => $killedCount,
                'errors' => $errors
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'killed' => 0,
                'errors' => [$e->getMessage()]
            ];
        }
    }

    /**
     * @throws \RuntimeException
     * @return void
     */
    private function startServer(): void{

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
        