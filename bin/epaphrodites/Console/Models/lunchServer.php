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
    private $phpServerPid = null;

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
     * 
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

            if ($this->isPortInUse($port, $address)) {
                throw new RuntimeException(sprintf(self::ERROR_PORT_IN_USE, $port));
            }

            // Configuration des gestionnaires de signaux pour un arrêt propre
            $this->setupSignalHandlers($output);

            $this->startServer($port, $address, $output, $input);

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
     * Configures signal handlers for clean shutdown
     */
    private function setupSignalHandlers(
        OutputInterface $output
    ){
        if (function_exists('pcntl_signal')) {
            pcntl_signal(SIGINT, function() use ($output) {
                $this->shutdown($output);
                exit(0);
            });
            
            pcntl_signal(SIGTERM, function() use ($output) {
                $this->shutdown($output);
                exit(0);
            });
        }
    }

    /**
     * Arrêt propre des serveurs
     */
    private function shutdown(OutputInterface $output)
    {
        $output->writeln("\n");
        $output->writeln("🛑 <comment>Shutting down servers...</comment>");
        
        if ($this->phpServerPid) {
            $this->stopProcess($this->phpServerPid);
            $output->writeln("✅ <info>PHP server stopped</info>");
        }
        
        if (_RUN_PYTHON_SERVER_ == true) {
            $pythonServer = new \Epaphrodites\epaphrodites\Console\Models\modelreloadPythonServer;
            $pythonServer->stopServer($output);
        }
        
        $output->writeln("👋 <info>All servers stopped. Goodbye!</info>");
    }

    /**
     * Start server by executing PHP built-in server command.
     * @param int $port The port number.
     */
    private function startServer(
        $port,
        $host,
        OutputInterface $output,
        InputInterface $input
    ){
        $output->writeln("╭─────────────────────────────────────────────────────────────╮");
        $output->writeln("│ 🔱  <info>Epaphrodites Framework — <fg=gray>Development Suite Booting...</></info>   │");
        $output->writeln("╰─────────────────────────────────────────────────────────────╯");
        $output->writeln("");
        $output->writeln("🚀 <fg=cyan>Launch Target</>:      <href=http://127.0.0.1:$port><fg=gray>http://127.0.0.1:$port</></>");
        $output->writeln("🎯 Mode:               <fg=gray>Development</>");
        $output->writeln("📦 Version:            <fg=gray>Epaphrodites v1.0.0</>");
        $output->writeln("");

        $this->startPhpServer($port, $host, $output);

        if (defined('_RUN_PYTHON_SERVER_') && _RUN_PYTHON_SERVER_ == true) {
            $pythonServer = new \Epaphrodites\epaphrodites\Console\Models\modelreloadPythonServer;
            $pythonResult = $pythonServer->startServer($input, $output, true);
            
            if ($pythonResult !== self::SUCCESS) {
                $output->writeln("<e>❌ Failed to start Python server</e>");
                // Arrêter le serveur PHP si Python a échoué
                if ($this->phpServerPid) {
                    $this->stopProcess($this->phpServerPid);
                }
                throw new RuntimeException("Python server startup failed");
            }
        } else {
            $output->writeln("<comment>(Note: Python server disabled — running PHP only mode)</comment>");
        }

        $output->writeln("");
        $output->writeln("╭─────────────────────────────────────────────────────────────╮");
        $output->writeln("│ 🎉 <info>All systems are online. Happy coding with Epaphrodites!</info>  │");
        $output->writeln("╰─────────────────────────────────────────────────────────────╯");
        $output->writeln("");
        $output->writeln("💡 <comment>Press Ctrl+C to stop all servers</comment>");

        $this->mainLoop($output);
    }

    /**
     * Start in background
     * 
     * @param int $port
     * @param string $host
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @throws \RuntimeException
     * @return void
     */
    private function startPhpServer(int $port, string $host, OutputInterface $output)
    {
        $logFile = _SERVER_LOG_;
        $command = "php -S $host:$port";
        
        if (PHP_OS_FAMILY === 'Windows') {
            $command = "start /B $command > $logFile 2>&1";
            $process = proc_open($command, [], $pipes);
            
            // Récupérer le PID sur Windows (plus complexe)
            sleep(1);
            $pidCommand = "wmic process where \"CommandLine like '%php -S%' and Name='php.exe'\" get ProcessId";
            $pidOutput = [];
            exec($pidCommand, $pidOutput);
            foreach ($pidOutput as $line) {
                if (is_numeric(trim($line))) {
                    $this->phpServerPid = (int) trim($line);
                    break;
                }
            }
        } else {
            $command = "$command > $logFile 2>&1 & echo $!";
            $output_array = [];
            exec($command, $output_array);
            $this->phpServerPid = !empty($output_array) ? (int) end($output_array) : null;
        }

        if (!$this->phpServerPid) {
            throw new RuntimeException("Failed to start the PHP server.");
        }

        $attempts = 0;
        $maxAttempts = 10;
        while ($attempts < $maxAttempts) {
            if ($this->isPortInUse($port, $host)) {
                $output->writeln("🖥️  <fg=green>PHP Server</>:         ✅ <info>Running</info>");
                $output->writeln("");
                return;
            }
            sleep(1);
            $attempts++;
        }

        throw new RuntimeException("PHP server did not start within the expected time.");
    }

    /**
     * Main loop that keeps the process alive
     * 
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return void
     */
    private function mainLoop(OutputInterface $output)
    {
        while (true) {

            if (function_exists('pcntl_signal_dispatch')) {
                pcntl_signal_dispatch();
            }

            if ($this->phpServerPid && !$this->isProcessRunning($this->phpServerPid)) {
                $output->writeln("<error>❌ PHP server has stopped unexpectedly</error>");
                break;
            }

            usleep(500000); 
        }
    }

    /**
     * Check if is running
     * 
     * @param mixed $pid
     * @return bool
     */
    private function isProcessRunning($pid)
    {
        if (!$pid) {
            return false;
        }

        if (PHP_OS_FAMILY === 'Windows') {
            $command = "tasklist /FI \"PID eq $pid\" /FO CSV /NH";
            $output = [];
            exec($command, $output, $returnCode);
            return $returnCode === 0 && !empty($output) && strpos($output[0], (string)$pid) !== false;
        } else {
            $command = "ps -p " . escapeshellarg($pid) . " > /dev/null 2>&1";
            exec($command, $output, $returnCode);
            return $returnCode === 0;
        }
    }

    /**
     * Stop a process by its PID
     */
    private function stopProcess($pid)
    {
        if (!$pid) {
            return false;
        }

        if (PHP_OS_FAMILY === 'Windows') {
            $command = "taskkill /F /PID " . escapeshellarg($pid);
        } else {
            $command = "kill " . escapeshellarg($pid);
        }

        exec($command, $output, $returnCode);
        return $returnCode === 0;
    }

    /**
     * Checks if the port is in use by executing a command based on the operating system.
     * @param int $port The port number.
     * @param string $host server host.
     * @return bool True if the port is in use, false otherwise.
     * @throws RuntimeException If the command execution fails.
     */
    private function isPortInUse(
        int $port, 
        string $host
    ): bool {
        $timeout = 1;
        $socket = @fsockopen($host, $port, $errorCode, $errorMessage, $timeout);
        
        if ($socket === false) {
            return false;
        }
        
        fclose($socket);
        return true;
    }
}