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
        try {
            $this->validatePort($port);
            if ($this->isPortInUse($port)) {
                throw new RuntimeException(sprintf(self::ERROR_PORT_IN_USE, $port));
            }
            $this->startServer($port, $output);
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
        OutputInterface $output
    ){
        $output->writeln("<info>🚀 Starting Epaphrodites development server...</info>");
        $output->writeln(sprintf("Target: <fg=gray>http://127.0.0.1:%d</fg=gray>", $port));
        $output->writeln("");
        $output->writeln("<bg=blue>[OK] Epaphrodites Server is running</bg=blue>");
        $output->writeln("");
        $output->writeln(sprintf("Development server is running at <fg=gray>http://127.0.0.1:%d</fg=gray>", $port));
        $output->writeln("<comment>Quit the server with CONTROL-C.</comment>");
    
        $command = "php -S 127.0.0.1:$port";
        $process = proc_open($command, [['pipe', 'r'], ['pipe', 'w'], ['pipe', 'w']], $pipes);
    
        if (is_resource($process)) {

            while ($line = fgets($pipes[1])) {
                $output->write($line);
            }
    
            fclose($pipes[0]);
            fclose($pipes[1]);
            fclose($pipes[2]);
    
            $exitCode = proc_close($process);
    
            $output->writeln("");
            $output->writeln(sprintf("<info>Server stopped with exit code %d</info>", $exitCode));
        } else {
            $output->writeln("<error>Failed to start the server.</error>");
        }
    }

    /**
     * Checks if the port is in use by executing a command based on the operating system.
     * @param int $port The port number.
     * @return string|null Output of the command execution.
     */
    private function isPortInUse($port)
    {
        $command = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') ? "netstat -an | findstr $port" : "lsof -i :$port";
        return shell_exec($command);
    }
}