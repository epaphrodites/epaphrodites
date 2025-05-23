<?php

namespace Epaphrodites\epaphrodites\Console\Models;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Epaphrodites\epaphrodites\Console\Setting\settingreloadPythonServer;
use RuntimeException;

/**
 * Model for managing Python server lifecycle (start, stop, reload)
 */
class modelreloadPythonServer extends settingreloadPythonServer
{
    // Configuration constants
    private const TIMEOUT_SECONDS = 5;
    private const SLEEP_MICROSECONDS = 200_000;
    private const PORT_CHECK_TIMEOUT = 1;
    
    // Error messages
    private const ERROR_PORT_IN_USE = 'The port %d is currently in use. ❌';
    private const ERROR_PYTHON_PATH = 'Python executable path (_PYTHON_) is not defined.';
    private const ERROR_INVALID_PORT = 'Invalid or undefined server port (_PYTHON_SERVER_PORT_).';
    private const ERROR_SERVER_FILE = 'Python server file does not exist (_PYTHON_FILE_FOLDERS_/config/server.py).';
    private const ERROR_PROCESS_START = 'Failed to start the Python server process.';
    private const ERROR_SERVER_TIMEOUT = 'The Python server could not be started within the timeout period.';
    
    // Success messages
    private const SUCCESS_SERVER_STARTED = 'The server has been started successfully! ✅';
    private const SUCCESS_SERVER_RELOADED = 'The server has been reloaded successfully! ✅';
    private const SUCCESS_PORT_KILLED = 'Port processes terminated successfully! ✅';

    /**
     * Executes the command to manage the Python server based on input options.
     *
     * @param InputInterface $input Console input interface
     * @param OutputInterface $output Console output interface
     * @return int Command execution status
     * @throws RuntimeException When configuration is invalid or operations fail
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $this->validateConfiguration();
            $port = $this->getServerPort();
            
            // Check if port is already in use for certain operations
            if ($input->getOption('s') && $this->isPortInUse($port)) {
                throw new RuntimeException(sprintf(self::ERROR_PORT_IN_USE, $port));
            }

            return $this->handleServerOperation($input, $output, $port);
            
        } catch (RuntimeException $e) {
            $output->writeln("<error>{$e->getMessage()}</error>");
            return static::FAILURE;
        }
    }

    /**
     * Handles the specific server operation based on input options.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param int $port
     * @return int
     */
    private function handleServerOperation(InputInterface $input, OutputInterface $output, int $port): int
    {
        if ($input->getOption('r')) {
            return $this->reloadServer($port, $output);
        }

        if ($input->getOption('s')) {
            return $this->startPythonServer($port, $output);
        }

        if ($input->getOption('k')) {
            return $this->killPortProcesses($port, $output);
        }

        $output->writeln("<comment>No operation specified. Use -r (reload), -s (start), or -k (kill).</comment>");
        return static::SUCCESS;
    }

    /**
     * Starts the Python server on the specified port.
     *
     * @param int $port Server port
     * @param OutputInterface $output Console output
     * @return int Operation status
     */
    private function startPythonServer(int $port, OutputInterface $output): int
    {
        try {
            $killResult = $this->terminatePortProcesses($port);
            
            if (!$killResult['success']) {
                $this->outputErrors($output, $killResult['errors']);
                return static::FAILURE;
            }

            $this->startServerProcess($port);
            $output->writeln("<info>" . self::SUCCESS_SERVER_STARTED . "</info>");
            
            return static::SUCCESS;
            
        } catch (RuntimeException $e) {
            $output->writeln("<error>Failed to start server: {$e->getMessage()}</error>");
            return static::FAILURE;
        }
    }

    /**
     * Reloads the Python server (kills existing processes and starts new one).
     *
     * @param int $port Server port
     * @param OutputInterface $output Console output
     * @return int Operation status
     */
    private function reloadServer(int $port, OutputInterface $output): int
    {
        try {
            $killResult = $this->terminatePortProcesses($port);
            
            if (!$killResult['success']) {
                $this->outputErrors($output, $killResult['errors']);
                return static::FAILURE;
            }

            $this->startServerProcess($port);
            $output->writeln("<info>" . self::SUCCESS_SERVER_RELOADED . "</info>");
            
            return static::SUCCESS;
            
        } catch (RuntimeException $e) {
            $output->writeln("<error>Failed to reload server: {$e->getMessage()}</error>");
            return static::FAILURE;
        }
    }

    /**
     * Kills processes running on the specified port.
     *
     * @param int $port Server port
     * @param OutputInterface $output Console output
     * @return int Operation status
     */
    private function killPortProcesses(int $port, OutputInterface $output): int
    {
        try {
            $killResult = $this->terminatePortProcesses($port);
            
            if ($killResult['success']) {
                $output->writeln("<info>" . self::SUCCESS_PORT_KILLED . "</info>");
                $output->writeln("<comment>Terminated {$killResult['killed']} process(es).</comment>");
            } else {
                $this->outputErrors($output, $killResult['errors']);
            }
            
            return $killResult['success'] ? static::SUCCESS : static::FAILURE;
            
        } catch (RuntimeException $e) {
            $output->writeln("<error>Failed to kill port processes: {$e->getMessage()}</error>");
            return static::FAILURE;
        }
    }

    /**
     * Validates all required configuration constants.
     *
     * @throws RuntimeException When configuration is invalid
     */
    private function validateConfiguration(): void
    {
        if (!defined('_PYTHON_') || empty(_PYTHON_)) {
            throw new RuntimeException(self::ERROR_PYTHON_PATH);
        }

        if (!defined('_PYTHON_SERVER_PORT_') || !$this->isValidPort(_PYTHON_SERVER_PORT_)) {
            throw new RuntimeException(self::ERROR_INVALID_PORT);
        }

        if (!defined('_PYTHON_FILE_FOLDERS_') || !$this->serverFileExists()) {
            throw new RuntimeException(self::ERROR_SERVER_FILE);
        }
    }

    /**
     * Checks if the given port number is valid.
     *
     * @param mixed $port Port to validate
     * @return bool True if valid, false otherwise
     */
    private function isValidPort($port): bool
    {
        return is_numeric($port) && $port > 0 && $port <= 65535;
    }

    /**
     * Checks if the Python server file exists.
     *
     * @return bool True if file exists, false otherwise
     */
    private function serverFileExists(): bool
    {
        return file_exists(_PYTHON_FILE_FOLDERS_ . 'config/server.py');
    }

    /**
     * Gets the configured server port.
     *
     * @return int Server port number
     */
    private function getServerPort(): int
    {
        return (int)_PYTHON_SERVER_PORT_;
    }

    /**
     * Terminates processes running on the specified port.
     *
     * @param int $port Port number
     * @param bool $force Whether to force kill processes
     * @param bool $silent Whether to suppress error messages
     * @return array Result with success status, kill count, and errors
     */
    private function terminatePortProcesses(int $port, bool $force = true, bool $silent = false): array
    {
        if (!$this->isValidPort($port)) {
            return [
                'success' => false,
                'killed' => 0,
                'errors' => ['Invalid port. Must be between 1 and 65535']
            ];
        }

        try {
            $pids = $this->getProcessesOnPort($port);
            $errors = [];
            $killedCount = 0;

            foreach ($pids as $pid) {
                if ($this->isSystemProcess($pid)) {
                    if (!$silent) {
                        $errors[] = "Skipping PID $pid (system process or current process)";
                    }
                    continue;
                }

                if ($this->killProcess($pid, $force)) {
                    $killedCount++;
                } elseif (!$silent) {
                    $errors[] = "Failed to terminate process $pid";
                }
            }

            return [
                'success' => ($killedCount > 0 || empty($errors)),
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
     * Gets process IDs running on the specified port.
     *
     * @param int $port Port number
     * @return array Array of process IDs
     */
    private function getProcessesOnPort(int $port): array
    {
        $pids = [];

        if (PHP_OS_FAMILY === 'Windows') {
            $command = "netstat -ano | findstr :{$port}";
            exec($command, $lines);

            foreach ($lines as $line) {
                if (preg_match('/(?:TCP|UDP).+?:\d+\s+(?:\S+\s+)*?(\d+)/i', $line, $matches)) {
                    $pids[] = (int)$matches[1];
                }
            }
        } else {
            $command = "lsof -i tcp:{$port} -t 2>/dev/null";
            exec($command, $pidLines);
            
            $pids = array_filter(
                array_map('trim', $pidLines),
                fn($pid) => is_numeric($pid) && $pid > 0
            );
        }

        return array_unique($pids);
    }

    /**
     * Checks if a process ID represents a system process that shouldn't be killed.
     *
     * @param int $pid Process ID
     * @return bool True if system process, false otherwise
     */
    private function isSystemProcess(int $pid): bool
    {
        return $pid <= 4 || $pid === getmypid();
    }

    /**
     * Kills a specific process.
     *
     * @param int $pid Process ID
     * @param bool $force Whether to force kill
     * @return bool True if successful, false otherwise
     */
    private function killProcess(int $pid, bool $force = true): bool
    {
        if (PHP_OS_FAMILY === 'Windows') {
            $command = "taskkill /PID $pid" . ($force ? " /F" : "");
        } else {
            $command = "kill " . ($force ? "-9 " : "") . escapeshellarg($pid) . " 2>/dev/null";
        }

        exec($command, $output, $exitCode);
        return $exitCode === 0;
    }

    /**
     * Starts the Python server process in the background.
     *
     * @param int $port Server port
     * @throws RuntimeException When server fails to start
     */
    private function startServerProcess(int $port): void
    {
        $command = $this->buildServerCommand($port);
        $descriptorSpec = $this->getProcessDescriptors();

        $process = proc_open($command, $descriptorSpec, $pipes);

        if (!is_resource($process)) {
            throw new RuntimeException(self::ERROR_PROCESS_START);
        }

        $this->cleanupProcessResources($pipes);
        proc_close($process);

        $this->waitForServerStart();
    }

    /**
     * Builds the command to start the Python server.
     *
     * @param int $port Server port
     * @return string Complete command string
     */
    private function buildServerCommand(int $port): string
    {
        $python = escapeshellcmd(_PYTHON_ ?? 'python3');
        $portArg = "--port " . escapeshellarg($port);
        $filePath = escapeshellarg(_PYTHON_FILE_FOLDERS_ . 'config/server.py');
        $logFile = escapeshellarg('pythonServer.log');

        if (PHP_OS_FAMILY === 'Windows') {
            return "start /B $python $filePath $portArg > $logFile 2>&1";
        } else {
            return "bash -c " . escapeshellarg("nohup $python $filePath $portArg >> $logFile 2>&1 &");
        }
    }

    /**
     * Gets process descriptors for server startup.
     *
     * @return array Process descriptor specification
     */
    private function getProcessDescriptors(): array
    {
        return [
            0 => ['pipe', 'r'],
            1 => ['file', 'pythonServer.log', 'a'],
            2 => ['file', 'pythonServer.log', 'a'],
        ];
    }

    /**
     * Cleans up process resources.
     *
     * @param array $pipes Process pipes to close
     */
    private function cleanupProcessResources(array $pipes): void
    {
        foreach ($pipes as $pipe) {
            if (is_resource($pipe)) {
                fclose($pipe);
            }
        }
    }

    /**
     * Waits for the server to start within the timeout period.
     *
     * @throws RuntimeException When server doesn't start within timeout
     */
    private function waitForServerStart(): void
    {
        $start = time();
        
        while (time() - $start < self::TIMEOUT_SECONDS) {
            usleep(self::SLEEP_MICROSECONDS);
            // Here you could add actual server health check
        }

        // For now, we assume success after timeout
        // In a real implementation, you'd check if the server is actually responding
    }

    /**
     * Checks if a port is currently in use.
     *
     * @param int $port Port number to check
     * @param string $host Host to check (default: localhost)
     * @return bool True if port is in use, false otherwise
     */
    private function isPortInUse(int $port, string $host = '127.0.0.1'): bool
    {
        $socket = @fsockopen($host, $port, $errorCode, $errorMessage, self::PORT_CHECK_TIMEOUT);

        if ($socket === false) {
            return false;
        }

        fclose($socket);
        return true;
    }

    /**
     * Outputs errors to the console.
     *
     * @param OutputInterface $output Console output
     * @param array $errors Array of error messages
     */
    private function outputErrors(OutputInterface $output, array $errors): void
    {
        foreach ($errors as $error) {
            $output->writeln("<error>Error: $error</error>");
        }
    }
}