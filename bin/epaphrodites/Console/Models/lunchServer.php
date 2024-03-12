<?php

namespace Epaphrodites\epaphrodites\Console\Models;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Epaphrodites\epaphrodites\Console\Setting\AddServerConfig;

class lunchServer extends AddServerConfig
{
    private const ERROR_PORT_IN_USE = 'The port %d is currently in use.❌';

    /**
     * Validates if the port number is within the valid range.
     * @param int $port The port number to validate.
     * @return bool True if the port is valid.
     * @throws \InvalidArgumentException If the port is invalid.
     */
    private function validatePort($port)
    {
        if (!is_numeric($port) || $port < 1 || $port > 65535) {
            throw new \InvalidArgumentException('Invalid port number.');
        }
        return true;
    }   
    
    /**
     * Validates if the provided host is a valid IP address.
     * @param string $host The host IP address to validate.
     * @return bool True if the host is valid.
     * @throws \InvalidArgumentException If the host IP address is invalid.
     */ 
    private function validateHost($host)
    {
        if (!filter_var($host, FILTER_VALIDATE_IP)) {
            throw new \InvalidArgumentException('Invalid host IP address.');
        }
        return true;
    }    

    /**
     * Executes the command to start the server.
     * @param string $host The host IP address.
     * @param int $port The port number.
     */
    protected function execute( 
        InputInterface $input, 
        OutputInterface $output
    ){
        $port = $input->getOption('port');

        try {
            $this->validatePort($port);

            if ($this->isPortInUse($port)) {
                throw new \RuntimeException(sprintf(self::ERROR_PORT_IN_USE, $port));
            }

            $this->startServer($port);
            return self::SUCCESS;

        } catch (\InvalidArgumentException $e) {
            $output->writeln("<error>Invalid argument: " . $e->getMessage() . "</error>");
            return self::FAILURE;
        } catch (\RuntimeException $e) {
            $output->writeln("<error>Runtime error: " . $e->getMessage() . "</error>");
            return self::FAILURE;
        }
    }

    /**
     * Start server by executing PHP built-in server command.
     * @param string $host The host IP address.
     * @param int $port The port number.
     */
    private function startServer($port)
    {
        echo "Starting the server on port $port, host 127.0.0.1...\n";
        shell_exec("php -S 127.0.0.1:$port");
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

