<?php

class InstallComponent
{
    /**
     * Array of extensions to install
     * @var array $extensions
     */
    private array $extensions = [
        'sqlite3',
        'pdo_sqlite',
        'gd',
        'intl',
        'fileinfo',
        'pdo_mysql'
    ];

    /**
     * Execute installation commands based on the OS
     * @return void
     */
    public function executeCommands(): void
    {
        // Get the operating system name
        $os = php_uname('s');

        // Use a match structure to call the appropriate installation method based on the OS
        match (true) {
            str_contains($os, 'Windows') => $this->installExtensionsOnWindows(),
            str_contains($os, 'Linux') => $this->installExtensionsOnLinux(),
            str_contains($os, 'Darwin') => $this->installExtensionsOnMacOS(),
            default => $this->handleUnsupportedOS($os),
        };

        //$this->updateComposer();
    }
    
    /**
     * Handle unsupported operating systems
     * @return void
     */
    private function handleUnsupportedOS(string $os): void
    {
        echo "Unsupported operating system: $os" . PHP_EOL;
    }

    /**
     * Execute a command and display its output in real-time
     * @return void
     */
    private function executeCommand(string $command): void
    {
        echo "Running command: $command" . PHP_EOL;

        $output = '';
        $status = 0;

        $handler = popen($command . ' 2>&1', 'r');
        while (!feof($handler)) {
            $char = fgetc($handler);
            $output .= $char;

            if ($char === "\n") {
                echo $output;
                $output = '';
            } elseif ($char === false) {
                break;
            } else {
                echo $this->getSpinner();
                usleep(100000);
            }
        }

        $status = pclose($handler);

        if ($status !== 0) {
            echo "Command execution failed with status: $status" . PHP_EOL;
            echo "State: $output" . PHP_EOL;
        }
    }

    /**
     * Detect the package manager used on Linux
     * @return string
     */
    private function detectPackageManager(): ?string
    {
        if (exec('command -v apt-get')) {
            return 'apt-get';
        } elseif (exec('command -v yum')) {
            return 'yum';
        } elseif (exec('command -v dnf')) {
            return 'dnf';
        } elseif (exec('command -v pacman')) {
            return 'pacman';
        }

        return null;
    }

    /**
     * Get the installation command based on the package manager
     * @return string
     */
    private function getInstallCommand(
        string $packageManager, 
        array $packages
    ): ?string{
        $installCommands = [
            'apt-get' => 'sudo apt-get install -y ',
            'yum' => 'sudo yum install -y ',
            'dnf' => 'sudo dnf install -y ',
            'pacman' => 'sudo pacman -S --noconfirm ',
        ];

        if (isset($installCommands[$packageManager])) {
            return $installCommands[$packageManager] . implode(' ', $packages);
        }

        return null;
    }    

    /**
     * Get an animated loading symbol
     * @return string
    */
    private function getSpinner(): string
    {
        static $spinner = ['-', '\\', '|', '/'];
        static $index = 0;

        $symbol = $spinner[$index];
        $index = ($index + 1) % count($spinner);

        return "\r$symbol";
    }

    /**
     * Install extensions on Linux
     * @return void
     */
    private function installExtensionsOnLinux(): void
    {
        echo "Installing extensions on Linux..." . PHP_EOL;

        $packages = [
            'php-sqlite3',
            'php-gd',
            'php-intl',
            'php-zip'
        ];

        // Detect the package manager and get the installation command
        $packageManager = $this->detectPackageManager();
        $installCommand = $this->getInstallCommand($packageManager, $packages);

        // Execute the installation command
        $this->executeCommand($installCommand);

        foreach ($this->extensions as $extension) {
            echo "\033[32m$extension............................done\033[0m" . PHP_EOL;
        }

        echo "\033[32mExtensions installed successfully on Linux.\033[0m ✅" . PHP_EOL;
    }

    /**
     * Install extensions on Windows
     * @return void
     */
    private function installExtensionsOnWindows(): void
{
    echo "Installing extensions on Windows..." . PHP_EOL;

    $phpIniPath = php_ini_loaded_file();
    if ($phpIniPath === false) {
        echo "Unable to locate the php.ini file." . PHP_EOL;
        return;
    }

    $phpIniContent = file_get_contents($phpIniPath);
    if ($phpIniContent === false) {
        echo "Unable to read the php.ini file." . PHP_EOL;
        return;
    }

    foreach ($this->extensions as $extension) {
        $extensionLine = "extension=$extension";
        if (strpos($phpIniContent, $extensionLine) !== 0) {
            if (strpos($phpIniContent, ";$extensionLine") !== false) {
                
                $phpIniContent = str_replace(";$extensionLine", $extensionLine, $phpIniContent);
                echo "\033[32m$extension............................done\033[0m" . PHP_EOL;
            } else {
                
                $phpIniContent .= "\n$extensionLine";
                echo "\033[32m$extension............................done\033[0m" . PHP_EOL;
            }
        }
    }

    if (file_put_contents($phpIniPath, $phpIniContent) === false) {
        echo "Unable to write to the php.ini file." . PHP_EOL;
        return;
    }

    echo "\033[32mExtensions installed successfully on Windows.\033[0m ✅" . PHP_EOL;
}

    /**
     * Install extensions on macOS
     * @return void
     */
    private function installExtensionsOnMacOS(): void
    {
        echo "Installing extensions on macOS..." . PHP_EOL;
    
        $packages = [
            'intl' => 'printf "\n" | sudo pecl install --force intl',
            'sqlite3' => 'brew install sqlite3',
            'gd' => 'brew install gd',
            'zip' => 'brew install zip',
        ];
    
        foreach ($packages as $extension => $installCommand) {
            $this->executeCommand($installCommand);
            echo "\033[32m$extension............................done\033[0m" . PHP_EOL;
        }
    
        echo "\033[32mExtensions installed successfully on macOS.\033[0m ✅" . PHP_EOL;
    }

    // /**
    //  * Update project dependencies and dump autoload
    //  * @return void
    //  */
    // private function updateComposer(): void
    // {
    //     echo "\nInstall, Updating project dependencies and dumping autoload..." . PHP_EOL;

    //     $tasks = [
    //         'update' => 'composer update',
    //         'dump-autoload' => 'composer dump-autoload',
    //     ];

    //     foreach ($tasks as $task => $command) {
    //         $this->executeCommand($command);
    //         echo "\033[32m$task............................done\033[0m" . PHP_EOL;
    //     }

    //     echo "\033[32mDependency update and autoload dump completed successfully.\033[0m ✅" . PHP_EOL;
    // }
}