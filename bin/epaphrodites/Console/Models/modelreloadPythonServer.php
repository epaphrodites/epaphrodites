<?php

namespace Epaphrodites\epaphrodites\Console\Models;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Command\Command;
use Epaphrodites\epaphrodites\Console\Setting\settingreloadPythonServer;
use RuntimeException;

/**
 * Model for managing Python server lifecycle (start, stop, reload)
 */
class modelreloadPythonServer extends settingreloadPythonServer
{

    /**
     * Executes the Python server.py in the context of a Symfony command
     * 
     * @param string $scriptPath Path to the server.py file
     * @param int $port Server port
     * @param string $host Server IP address (default 127.0.0.1)
     * @param bool $background Run in background (default true)
     * @param OutputInterface|null $output Symfony output interface (optional)
     * @return array Execution result
     */
    protected function executePythonServer($scriptPath, $port, $host = '127.0.0.1', $background = true, $output = null) 
    {
        if (!file_exists($scriptPath)) {
            $error = "The file $scriptPath does not exist";
            $output->writeln("<error>$error</error>");
            return ['success' => false, 'error' => $error, 'output' => null, 'pid' => null];
        }

        $output->writeln("<info>Starting Python server...</info>");
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

            exec($command, $output_array, $returnCode);

            sleep(1);

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

        if ($output) {
            $output->writeln("<info>Command executed: $command</info>");
            if ($result['success']) {
                $output->writeln("<comment>Server launched successfully" . ($pid ? " (PID: $pid)" : "") . "</comment>");
            } else {
                $output->writeln("<error>Launch failed: {$result['error']}</error>");
                $output->writeln("<comment>Output: " . implode("\n", $output_array) . "</comment>");
            }
        }

        return $result;
    }

    /**
     * Checks if the Python server is running
     * 
     * @param int $port Server port
     * @param string $host Server IP address
     * @param OutputInterface|null $output Symfony output interface (optional)
     * @return bool True if the server responds, false otherwise
     */
    protected function isPythonServerRunning($port, $host = '127.0.0.1', $output = null) 
    {
        $connection = @fsockopen($host, $port, $errno, $errstr, 1);
        if ($connection) {
            fclose($connection);
            if ($output) {
                $output->writeln("<info>✅ Python server active </info>");
            }
            return true;
        }
        
        if ($output) {
            $output->writeln("<comment>Python server not accessible </comment>");
        }
        return false;
    }

    /**
     * Stops a Python process by its PID
     * 
     * @param int $pid Process PID
     * @param OutputInterface|null $output Symfony output interface (optional)
     * @return bool True if the process was stopped, false otherwise
     */
    protected function stopPythonServer($pid, $output = null) 
    {
        if (!$pid) {
            if ($output) {
                $output->writeln("<comment>No PID provided</comment>");
            }
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
        if ($output) {
            if ($success) {
                $output->writeln("<comment>✅ Process $pid stopped</comment>");
            } else {
                $output->writeln("<error>❌ Unable to stop process $pid</error>");
            }
        }
        
        return $success;
    }

    /**
     * Finds and stops all Python processes using a specific port
     * 
     * @param int $port Port to free
     * @param OutputInterface|null $output Symfony output interface (optional)
     * @return array Operation result
     */
    protected function killPythonServerByPort($port, $output = null) 
    {
        if ($output) {
            $output->writeln("<info>Searching for processes using port $port...</info>");
        }
        
        if (PHP_OS_FAMILY === 'Windows') {
            $command = "netstat -ano | findstr :$port";
            $cmd_output = [];
            exec($command, $cmd_output);
            
            $pids = [];
            foreach ($cmd_output as $line) {
                if (preg_match('/\s+(\d+)$/', $line, $matches)) {
                    $pids[] = $matches[1];
                }
            }
            
            $killed = [];
            foreach (array_unique($pids) as $pid) {
                if ($this->stopPythonServer($pid, $output)) {
                    $killed[] = $pid;
                }
            }
            
            $result = [
                'success' => !empty($killed),
                'killed_pids' => $killed,
                'message' => empty($killed) ? "No processes found on port $port" : "Processes stopped: " . implode(', ', $killed)
            ];
            
            if ($output) {
                $output->writeln("<comment>{$result['message']}</comment>");
            }
            
            return $result;
        } else {
            $command = "lsof -ti:$port | xargs kill -9";
            $output_array = [];
            $returnCode = 0;
            exec($command, $output_array, $returnCode);
            
            $result = [
                'success' => $returnCode === 0,
                'killed_pids' => [],
                'message' => $returnCode === 0 ? "Processes on port $port stopped" : "No processes found or error"
            ];
            
            if ($output) {
                $output->writeln("<comment>{$result['message']}</comment>");
            }
            
            return $result;
        }
    }

    /**
     * Execute method for Symfony Console command
     * Handles options -s (start), -r (reload), -k (kill)
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        // Check which option was passed
        $start = $input->getOption('start');
        $reload = $input->getOption('reload');
        $kill = $input->getOption('kill');

        // Verify only one option is used
        $optionsCount = ($start ? 1 : 0) + ($reload ? 1 : 0) + ($kill ? 1 : 0);
        if ($optionsCount > 1) {
            $output->writeln('<error>Error: Please specify only one option (-s, -r, or -k).</error>');
            return Command::FAILURE;
        }
        if ($optionsCount === 0) {
            $output->writeln('<error>Error: No option specified. Use -s (start), -r (reload), or -k (stop).</error>');
            return Command::FAILURE;
        }

        // Execute the corresponding action
        if ($start) {
            $output->writeln("<info>🚀 Attempting to start Python server ...</info>");
            return $this->startServer($input, $output);
        } elseif ($reload) {
            $output->writeln("<info>🔄 Attempting to reload Python server ...</info>");
            return $this->reloadServer($input, $output);
        } elseif ($kill) {
            $output->writeln("<info>🛑 Attempting to stop Python server ...</info>");
            return $this->stopServer($output);
        }

        return Command::FAILURE;
    }

    /**
     * Method to start the Python server
     */
    public function startServer(InputInterface $input, OutputInterface $output): int
    {
        $port = _PYTHON_SERVER_PORT_;
        $host = '127.0.0.1';
        $filePath = _PYTHON_FILE_FOLDERS_ . 'config/server.py';

        // Check if the server is already running
        if ($this->isPythonServerRunning($port, $host, $output)) {
            $output->writeln("<comment>⚠️ The server is already running .</comment>");
            return Command::SUCCESS;
        }

        // Launch the server
        $result = $this->executePythonServer($filePath, $port, $host, true, $output);

        if (!$result['success']) {
            $output->writeln("<error>❌ Failed to launch Python server: {$result['error']}</error>");
            return Command::FAILURE;
        }

        // Wait for the server to start
        $output->writeln('<comment>⏳ Waiting for server to start...</comment>');
        $attempts = 0;
        $maxAttempts = 10;

        while ($attempts < $maxAttempts) {
            sleep(1);
            if ($this->isPythonServerRunning($port, $host)) {
                $output->writeln("<info>✅ Python server started successfully in background</info>");
                $output->writeln("<comment>Accessible...................✅</comment>");
                if ($result['pid']) {
                    $output->writeln("<comment>📋 Process PID..............{$result['pid']}</comment>");
                }
                return Command::SUCCESS;
            }
            $attempts++;
        }

        $output->writeln("<error>❌ Server did not respond after $maxAttempts attempts</error>");
        if ($result['pid']) {
            $this->stopPythonServer($result['pid'], $output);
        }

        return Command::FAILURE;
    }

    /**
     * Method to stop the Python server
     * 
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return int
     */
    public function stopServer(OutputInterface $output): int
    {
        $port = _PYTHON_SERVER_PORT_;
        $host = '127.0.0.1';

        if (!$this->isPythonServerRunning($port, $host, $output)) {
            $output->writeln("<comment>⚠️ No Python server running </comment>");
            return Command::SUCCESS;
        }

        $killResult = $this->killPythonServerByPort($port, $output);

        if ($killResult['success']) {
            $output->writeln("<info>✅ Python server stopped successfully!</info>");
            if (!empty($killResult['killed_pids'])) {
                $output->writeln("<comment>📋 Stopped PIDs: " . implode(', ', $killResult['killed_pids']) . "</comment>");
            }
            return Command::SUCCESS;
        } else {
            $output->writeln("<error>❌ Failed to stop Python server: {$killResult['message']}</error>");
            return Command::FAILURE;
        }
    }

    /**
     * Method to reload the Python server
     */
    public function reloadServer(InputInterface $input, OutputInterface $output): int
    {
        $port = _PYTHON_SERVER_PORT_;
        $host = '127.0.0.1';

        $output->writeln("<info>🔄 Reloading Python server</info>");

        // Stop the server
        $stopResult = $this->stopServer($output);
        if ($stopResult !== Command::SUCCESS) {
            return $stopResult;
        }

        // Wait briefly
        sleep(2);

        // Restart
        return $this->startServer($input, $output);
    }
}