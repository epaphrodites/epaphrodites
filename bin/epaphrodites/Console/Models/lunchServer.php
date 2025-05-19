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

           # $this->startServer($port, $address, $output);

            $this->runPythonServer($port, $address, $output);

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

private function runPythonServer(
    string $port = "5000",
    string $address = "127.0.0.1",
    OutputInterface $output
): void {
    // Correction du chemin vers le fichier Python
    // Attention: vérifiez si cette construction de chemin est correcte pour votre projet
    $scriptPath = rtrim(_PYTHON_FILE_FOLDERS_, '/') . "/config/server.py";
    $pythonExecutable = _PYTHON_;
    
    // Vérifier si le fichier existe et afficher le chemin complet
    if (!file_exists($scriptPath)) {
        $output->writeln("❌ Erreur : Le fichier $scriptPath n'existe pas.");
        return;
    }
    
    // Construire la commande avec le bon échappement des arguments
    $command = sprintf(
        '%s %s %s %s',
        escapeshellcmd($pythonExecutable),
        escapeshellarg($scriptPath),
        escapeshellarg($port),
        escapeshellarg($address)
    );
    
    // Configuration pour capturer la sortie
    $descriptorspec = [
        0 => ["pipe", "r"],  // stdin
        1 => ["pipe", "w"],  // stdout - capture de la sortie 
        2 => ["pipe", "w"]   // stderr - capture des erreurs
    ];
    
    // Lancer le processus en arrière-plan
    $process = proc_open($command, $descriptorspec, $pipes);
    
    if (is_resource($process)) {
        // Laisser le temps au serveur de démarrer
        sleep(1);
        
        // Lire la sortie et les erreurs
        $stdout = stream_get_contents($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        
        // Fermer les pipes
        fclose($pipes[0]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        
        // Afficher les messages de sortie
        if (strpos($stdout, "Serveur Python démarré") !== false) {
            $output->writeln("✅ Serveur Python lancé sur http://$address:$port");
        } else {
            $output->writeln("⚠️ Démarrage du serveur Python...");
            $output->writeln("http://$address:$port");
            
            if (!empty($stderr)) {
                $output->writeln("⚠️ Message d'erreur du serveur Python:");
                $output->writeln($stderr);
            }
        }
    } else {
        $output->writeln("❌ Erreur : impossible de démarrer le serveur Python.");
    }
}

}