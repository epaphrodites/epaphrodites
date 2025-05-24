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

           $this->startServer($port, $address, $output, $input );

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
        OutputInterface $output,
        InputInterface $input
    ){

        $output->writeln("╭─────────────────────────────────────────────────────────────╮");
        $output->writeln("│ 🔱  <info>Epaphrodites Framework — <fg=gray>Development Suite Booting...</></info>   │");
        $output->writeln("╰─────────────────────────────────────────────────────────────╯");
        $output->writeln("");
        $output->writeln("🚀 <fg=cyan>Launch Target</>:      <href=http://127.0.0.1:8000><fg=gray>http://127.0.0.1:8000</></>");
        $output->writeln("🎯 Mode:               <fg=gray>Development</>");
        $output->writeln("📦 Version:            <fg=gray>Epaphrodites v1.0.0</>");
        $output->writeln("");
        $output->writeln("🖥️  <fg=green>PHP Server</>:        ✅ <info>Running</info>");
        $output->writeln("   └── Stop with:       <comment>CTRL + C</comment>");
        $output->writeln("");
        $logFile = _SERVER_LOG_;
        $command = "php -S $host:$port > $logFile 2>&1";
        $process = proc_open($command, [['pipe', 'r'], ['pipe', 'w'], ['pipe', 'w']], $pipes);
    
        if (!is_resource($process)) {
            throw new RuntimeException("Failed to start the server.");
        }
  
        if(_RUN_PYTHON_SERVER_ == true){
            $pythonServer = new \Epaphrodites\epaphrodites\Console\Models\modelreloadPythonServer;
            $pythonServer->startServer( $input , $output, true);
        }

        if(_RUN_PYTHON_SERVER_ == false){
             $output->writeln("<comment>(Note: Python server not detected — running PHP only mode)</comment>");
        }
    
        while (proc_get_status($process)['running']) {
            usleep(100000);
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