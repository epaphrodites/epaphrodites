<?php

namespace Epaphrodites\epaphrodites\Console\Models;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Epaphrodites\epaphrodites\Console\Setting\AddServerConfig;
use InvalidArgumentException;
use RuntimeException;

class LunchServer extends AddServerConfig
{
    private const ERROR_PORT_IN_USE = 'The port %d is currently in use.❌';

    /**
     * Validates if the port number is within the valid range.
     * @param int $port The port number to validate.
     * @return bool True if the port is valid.
     * @throws InvalidArgumentException If the port is invalid.
     */
    private function validatePort($port)
    {
        if (!is_numeric($port) || $port < 1 || $port > 65535) {
            throw new InvalidArgumentException('Invalid port number.');
        }
        return true;
    }

    /**
     * Executes the command to start the server.
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ){
        $port = $input->getOption('port');
        $address = "127.0.0.1";
        try {

            $this->validatePort($port);

            $this->killPythonPort(_PYTHON_SERVER_PORT_);

            if ($this->isPortInUse($port, $address)) {
                throw new RuntimeException(sprintf(self::ERROR_PORT_IN_USE, $port));
            }

           $this->startServer($port, $address, $output);

           return self::SUCCESS;

        } catch (InvalidArgumentException $e) {
            $output->writeln("<error>Invalid argument: " . $e->getMessage() . "</error>");
            return self::FAILURE;
        } catch (RuntimeException $e) {
            $output->writeln("<error>Runtime error: " . $e->getMessage() . "</error>");
            return self::FAILURE;
        }
    }

    /**
     * Start server by executing PHP built-in server command.
     * @param int $port The port number.
     */
    private function startServer(
        $port,
        $host,
        OutputInterface $output
    ){
        $output->writeln("<info>🚀 Starting Epaphrodites development server...</info>");
        $output->writeln(sprintf("Target: <fg=gray>http://$host:%d</fg=gray>", $port));
        $output->writeln("");
        $output->writeln("<bg=blue>[OK] Epaphrodites Server is running</bg=blue>");
        $output->writeln("");
        $output->writeln(sprintf("Development server is running at <fg=gray>http://$host:%d</fg=gray>", $port));
        $output->writeln("<comment>Quit the server with CONTROL-C.</comment>");
    
        $logFile = _SERVER_LOG_;
        $command = "php -S $host:$port > $logFile 2>&1";
        $process = proc_open($command, [['pipe', 'r'], ['pipe', 'w'], ['pipe', 'w']], $pipes);
    
        if (!is_resource($process)) {
            throw new RuntimeException("Failed to start the server.");
        }
    
        while (proc_get_status($process)['running']) {
            usleep(100000); // Wait for 100ms
        }
    
        $exitCode = proc_close($process);
        if ($exitCode !== 0) {
            throw new RuntimeException(sprintf("Server exited with code %d", $exitCode));
        }
    
        $output->writeln("");
        $output->writeln(sprintf("<info>Server stopped with exit code %d</info>", $exitCode));
    }

    /**
     * Checks if the port is in use by executing a command based on the operating system.
     * @param int $port The port number.
     * @return bool True if the port is in use, false otherwise.
     * @throws RuntimeException If the command execution fails.
     */
    private function isPortInUse($port , $host)
    {
        $timeout = 1;
    
        $socket = @fsockopen($host, $port, $errorCode, $errorMessage, $timeout);
    
        if ($socket === false) {
            return false;
        }
    
        fclose($socket);
        return true;
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
}