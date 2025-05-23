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
     * Exécute le serveur Python server.py dans le contexte d'une commande Symfony
     * 
     * @param string $scriptPath Chemin vers le fichier server.py
     * @param int $port Port du serveur
     * @param string $host Adresse IP du serveur (par défaut 127.0.0.1)
     * @param bool $background Exécuter en arrière-plan (par défaut true)
     * @param OutputInterface|null $output Interface de sortie Symfony (optionnel)
     * @return array Résultat de l'exécution
     */
    protected function executePythonServer($scriptPath, $port, $host = '127.0.0.1', $background = true, $output = null) 
    {
        if (!file_exists($scriptPath)) {
            $error = "Le fichier $scriptPath n'existe pas";
            $output->writeln("<error>$error</error>");
            return ['success' => false, 'error' => $error, 'output' => null, 'pid' => null];
        }

        $output->writeln("<info>Lancement du serveur Python sur $host:$port</info>");
        $command = "python " . escapeshellarg($scriptPath) . " --host=" . escapeshellarg($host) . " --port=" . escapeshellarg($port);

        if ($background) {
            if (PHP_OS_FAMILY === 'Windows') {
                // Windows: Exécuter en arrière-plan et récupérer le PID
                $command = "start /B " . $command . " > nul 2>&1";
                $pidCommand = "wmic process where \"CommandLine like '%" . basename($scriptPath) . "%' and Name='python.exe'\" get ProcessId";
            } else {
                // Linux/Unix/Mac: Exécuter en arrière-plan et récupérer le PID
                $command = $command . " > /dev/null 2>&1 & echo $!";
            }
        }

        $output_array = [];
        $returnCode = 0;

        if ($background && PHP_OS_FAMILY === 'Windows') {
            // Exécuter la commande en arrière-plan
            exec($command, $output_array, $returnCode);
            // Attendre un court instant pour que le processus démarre
            sleep(1);
            // Récupérer le PID
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
                'error' => $returnCode !== 0 ? "Erreur lors du lancement (code: $returnCode)" : null,
                'output' => $output_array,
                'pid' => $pid,
                'background' => true
            ];
        } else {
            exec($command, $output_array, $returnCode);
            $pid = PHP_OS_FAMILY !== 'Windows' && $background ? (int) end($output_array) : null;
            $result = [
                'success' => $returnCode === 0,
                'error' => $returnCode !== 0 ? "Erreur lors de l'exécution (code: $returnCode)" : null,
                'output' => $output_array,
                'pid' => $pid,
                'background' => $background
            ];
        }

        if ($output) {
            $output->writeln("<info>Commande exécutée : $command</info>");
            if ($result['success']) {
                $output->writeln("<comment>Serveur lancé avec succès" . ($pid ? " (PID: $pid)" : "") . "</comment>");
            } else {
                $output->writeln("<error>Échec du lancement: {$result['error']}</error>");
                $output->writeln("<comment>Sortie: " . implode("\n", $output_array) . "</comment>");
            }
        }

        return $result;
    }

    /**
     * Vérifie si le serveur Python est en cours d'exécution
     * 
     * @param int $port Port du serveur
     * @param string $host Adresse IP du serveur
     * @param OutputInterface|null $output Interface de sortie Symfony (optionnel)
     * @return bool True si le serveur répond, false sinon
     */
    protected function isPythonServerRunning($port, $host = '127.0.0.1', $output = null) 
    {
        $connection = @fsockopen($host, $port, $errno, $errstr, 1);
        if ($connection) {
            fclose($connection);
            if ($output) {
                $output->writeln("<info>✅ Serveur Python actif sur http://$host:$port</info>");
            }
            return true;
        }
        
        if ($output) {
            $output->writeln("<comment>Serveur Python non accessible sur http://$host:$port</comment>");
        }
        return false;
    }

    /**
     * Arrête un processus Python par son PID
     * 
     * @param int $pid PID du processus
     * @param OutputInterface|null $output Interface de sortie Symfony (optionnel)
     * @return bool True si le processus a été arrêté, false sinon
     */
    protected function stopPythonServer($pid, $output = null) 
    {
        if (!$pid) {
            if ($output) {
                $output->writeln("<comment>Aucun PID fourni</comment>");
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
                $output->writeln("<comment>✅ Processus $pid arrêté</comment>");
            } else {
                $output->writeln("<error>❌ Impossible d'arrêter le processus $pid</error>");
            }
        }
        
        return $success;
    }

    /**
     * Trouve et arrête tous les processus Python qui utilisent un port spécifique
     * 
     * @param int $port Port à libérer
     * @param OutputInterface|null $output Interface de sortie Symfony (optionnel)
     * @return array Résultat de l'opération
     */
    protected function killPythonServerByPort($port, $output = null) 
    {
        if ($output) {
            $output->writeln("<info>Recherche des processus utilisant le port $port...</info>");
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
                'message' => empty($killed) ? "Aucun processus trouvé sur le port $port" : "Processus arrêtés: " . implode(', ', $killed)
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
                'message' => $returnCode === 0 ? "Processus sur le port $port arrêtés" : "Aucun processus trouvé ou erreur"
            ];
            
            if ($output) {
                $output->writeln("<comment>{$result['message']}</comment>");
            }
            
            return $result;
        }
    }

    /**
     * Méthode execute pour commande Symfony Console
     * Gère les options -s (start), -r (reload), -k (kill)
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $port = _PYTHON_SERVER_PORT_;
        $host = '127.0.0.1';
        $filePath = _PYTHON_FILE_FOLDERS_ . 'config/server.py';

        // Vérifier quelle option a été passée
        $start = $input->getOption('start');
        $reload = $input->getOption('reload');
        $kill = $input->getOption('kill');

        // Vérifier qu'une seule option est utilisée
        $optionsCount = ($start ? 1 : 0) + ($reload ? 1 : 0) + ($kill ? 1 : 0);
        if ($optionsCount > 1) {
            $output->writeln('<error>Erreur : Veuillez spécifier une seule option (-s, -r ou -k).</error>');
            return Command::FAILURE;
        }
        if ($optionsCount === 0) {
            $output->writeln('<error>Erreur : Aucune option spécifiée. Utilisez -s (démarrer), -r (redémarrer) ou -k (arrêter).</error>');
            return Command::FAILURE;
        }

        // Exécuter l'action correspondante
        if ($start) {
            $output->writeln("<info>🚀 Tentative de démarrage du serveur Python sur http://$host:$port...</info>");
            return $this->startServer($input, $output);
        } elseif ($reload) {
            $output->writeln("<info>🔄 Tentative de redémarrage du serveur Python sur http://$host:$port...</info>");
            return $this->reloadServer($input, $output);
        } elseif ($kill) {
            $output->writeln("<info>🛑 Tentative d'arrêt du serveur Python sur http://$host:$port...</info>");
            return $this->stopServer($output);
        }

        return Command::FAILURE;
    }

    /**
     * Méthode pour démarrer le serveur Python
     */
    public function startServer(InputInterface $input, OutputInterface $output): int
    {
        $port = _PYTHON_SERVER_PORT_;
        $host = '127.0.0.1';
        $filePath = _PYTHON_FILE_FOLDERS_ . 'config/server.py';

        // Vérifier si le serveur est déjà en cours
        if ($this->isPythonServerRunning($port, $host, $output)) {
            $output->writeln("<comment>⚠️ Le serveur est déjà en cours d'exécution sur http://$host:$port.</comment>");
            return Command::SUCCESS;
        }

        // Lancer le serveur
        $result = $this->executePythonServer($filePath, $port, $host, true, $output);

        if (!$result['success']) {
            $output->writeln("<error>❌ Échec du lancement du serveur Python: {$result['error']}</error>");
            return Command::FAILURE;
        }

        // Attendre que le serveur démarre
        $output->writeln('<comment>⏳ Attente du démarrage du serveur...</comment>');
        $attempts = 0;
        $maxAttempts = 10;

        while ($attempts < $maxAttempts) {
            sleep(1);
            if ($this->isPythonServerRunning($port, $host)) {
                $output->writeln("<info>✅ Serveur Python démarré avec succès !</info>");
                $output->writeln("<comment>🌐 Accessible sur http://$host:$port</comment>");
                if ($result['pid']) {
                    $output->writeln("<comment>📋 PID du processus: {$result['pid']}</comment>");
                }
                return Command::SUCCESS;
            }
            $attempts++;
        }

        $output->writeln("<error>❌ Le serveur ne répond pas après $maxAttempts tentatives</error>");
        if ($result['pid']) {
            $this->stopPythonServer($result['pid'], $output);
        }

        return Command::FAILURE;
    }

    /**
     * Méthode pour arrêter le serveur Python
     */
    public function stopServer(OutputInterface $output): int
    {
        $port = _PYTHON_SERVER_PORT_;
        $host = '127.0.0.1';

        if (!$this->isPythonServerRunning($port, $host, $output)) {
            $output->writeln("<comment>⚠️ Aucun serveur Python en cours d'exécution sur http://$host:$port</comment>");
            return Command::SUCCESS;
        }

        $killResult = $this->killPythonServerByPort($port, $output);

        if ($killResult['success']) {
            $output->writeln("<info>✅ Serveur Python arrêté avec succès !</info>");
            if (!empty($killResult['killed_pids'])) {
                $output->writeln("<comment>📋 PIDs arrêtés: " . implode(', ', $killResult['killed_pids']) . "</comment>");
            }
            return Command::SUCCESS;
        } else {
            $output->writeln("<error>❌ Échec de l'arrêt du serveur Python: {$killResult['message']}</error>");
            return Command::FAILURE;
        }
    }

    /**
     * Méthode pour redémarrer le serveur Python
     */
    public function reloadServer(InputInterface $input, OutputInterface $output): int
    {
        $port = _PYTHON_SERVER_PORT_;
        $host = '127.0.0.1';

        $output->writeln("<info>🔄 Redémarrage du serveur Python sur http://$host:$port...</info>");

        // Arrêter le serveur
        $stopResult = $this->stopServer($output);
        if ($stopResult !== Command::SUCCESS) {
            return $stopResult;
        }

        // Attendre un peu
        sleep(2);

        // Redémarrer
        return $this->startServer($input, $output);
    }
}