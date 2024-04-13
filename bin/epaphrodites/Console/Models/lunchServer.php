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
}