<?php

namespace Epaphrodites\epaphrodites\Console\Models;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Epaphrodites\epaphrodites\Console\Setting\settingreloadPythonServer;
use RuntimeException;

class modelreloadPythonServer extends settingreloadPythonServer
{
    private const TIMEOUT_SECONDS = 5;
    private const SLEEP_MICROSECONDS = 200_000;

    /**
     * Executes the command to restart the Python server.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            // Validate configuration constants
            $this->validateConfig();

            // Kill existing processes on the specified port
            $killResult = $this->killPythonPort(_PYTHON_SERVER_PORT_);

            if (!$killResult['success'] && !empty($killResult['errors'])) {
                foreach ($killResult['errors'] as $error) {
                    $output->writeln("<error>Error: $error</error>");
                }
                return static::FAILURE;
            }

            // Start the Python server
            $this->startServer();

            $output->writeln("<info>The server has been reloaded successfully! ✅</info>");
            return static::SUCCESS;
        } catch (RuntimeException $e) {
            $output->writeln("<error>Failed to restart server: {$e->getMessage()}</error>");
            return static::FAILURE;
        }
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

    /**
     * Stops all processes listening on a specified TCP port.
     *
     * @param int $port The TCP port to terminate processes on
     * @param bool $force If true, forces process termination
     * @param bool $silent If true, suppresses error messages
     * @return array{success: bool, killed: int, errors: array<string>} Operation result
     */
    private function killPythonPort(int $port, bool $force = true, bool $silent = false): array
    {
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
                    if (preg_match('/(?:TCP|UDP).+?:\d+\s+(?:\S+\s+)*?(\d+)/i', $line, $matches)) {
                        $pids[] = (int)$matches[1];
                    }
                }

                foreach (array_unique($pids) as $pid) {
                    if ($pid <= 4 || $pid === getmypid()) {
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

                $pids = array_filter(array_map('trim', $pids), fn($pid) => is_numeric($pid) && $pid > 0 && $pid != getmypid());

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
                'success' => ($killedCount > 0 || count($errors) === 0),
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
     * Starts the Python server in the background.
     *
     * @throws RuntimeException
     */
    private function startServer(): void
    {
        if ($this->isRunning()) {
            return;
        }

        $python = escapeshellcmd(_PYTHON_ ?? 'python3');
        $port = "--port " . escapeshellarg((string)_PYTHON_SERVER_PORT_);
        $filePath = escapeshellarg(_PYTHON_FILE_FOLDERS_ . 'config/server.py');
        $logFile = escapeshellarg('pythonServer.log');

        $isWindows = PHP_OS_FAMILY === 'Windows';
        $command = $isWindows
            ? "start /B $python $filePath $port > $logFile 2>&1"
            : "bash -c " . escapeshellarg("nohup $python $filePath $port >> $logFile 2>&1 &");

        $descriptorspec = [
            0 => ['pipe', 'r'],
            1 => ['file', 'pythonServer.log', 'a'],
            2 => ['file', 'pythonServer.log', 'a'],
        ];

        $process = proc_open($command, $descriptorspec, $pipes);

        if (!is_resource($process)) {
            throw new RuntimeException('Failed to start the Python server process.');
        }

        foreach ($pipes as $pipe) {
            if (is_resource($pipe)) {
                fclose($pipe);
            }
        }
        proc_close($process);

        $start = time();
        while (time() - $start < self::TIMEOUT_SECONDS) {
            if ($this->isRunning()) {
                return;
            }
            usleep(self::SLEEP_MICROSECONDS);
        }

        throw new RuntimeException('The Python server could not be started within the timeout period.');
    }

    /**
     * Checks if the server is running on the specified port.
     *
     * @return bool
     */
    private function isRunning(): bool
    {
        $port = (int)(_PYTHON_SERVER_PORT_ ?? 5000);
        $fp = @fsockopen('127.0.0.1', $port, $errno, $errstr, 1.0);

        if ($fp) {
            fclose($fp);
            return true;
        }

        return false;
    }
}