<?php

namespace Epaphrodites\epaphrodites\Console\Models;

use Epaphrodites\database\query\Builders;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Epaphrodites\epaphrodites\Console\Setting\settinggearshift;

class modelgearshift extends settinggearshift
{

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        # Get console arguments
        $action = $input->getArgument('type');

        $results = $this->newMigration($action);

        if($results === true ){

            $output->writeln("<info>All migration has been successfully created!!!✅</info>");
            return self::SUCCESS;
        }else{
            $output->writeln("<error>Sorry, check your request before starting the migration ❌</error>");
            return self::FAILURE;
        }        
    }

    /**
     * Search for and execute PHP migrations in the specified directory.
     * @return bool Returns true if a migration was executed successfully, otherwise false.
     */
    private function newMigration($action): bool
    {
        // Path to the migrations directory
        $directory = _DIR_MIGRATION_;

        $files = scandir($directory);
        $files = array_diff($files, array('.', '..'));
        sort($files);

        // Iterate through each file in the directory
        foreach ($files as $file) {
            $filePath = $directory . '/' . $file;

            // Check if the file is a PHP file
            if (is_file($filePath) && pathinfo($filePath, PATHINFO_EXTENSION) === 'php') {
                // Read the file content using stream_get_contents
                $fileContent = stream_get_contents(fopen($filePath, 'r'));

                if (preg_match('/class\s+([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\s+extends buildGearShift/', $fileContent, $matches)) {
                    $migrationClass = $matches[1];

                    require $filePath;

                    if (class_exists($migrationClass)) {
                        $migration = new $migrationClass;

                        // Execute the 'up' method if it exists
                        if (method_exists($migration, 'up')) {
                            $getUp = $migration->up();
                            if(!empty($getUp)){
                                $this->executeQuery($getUp["request"], $getUp["db"]);
                            }
                        }

                        // Execute the 'down' method if it exists
                        if (method_exists($migration, 'down')) {
                            $getDown = $migration->down();
                            if(!empty($getDown)){
                                $this->executeQuery($getDown["request"], $getDown["db"]);
                            }
                        }

                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            }
        }

        return false;
    }

    /**
     * Execute the database query.
     * @param string $queryChaine
     * @return void
     */
    private function executeQuery(string $queryChaine , int $db):void
    {

        $database = new Builders;
        $database->chaine($queryChaine)->setQuery($db);
    }
}
