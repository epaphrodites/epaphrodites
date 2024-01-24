<?php

namespace Epaphrodites\epaphrodites\Console\Models;

use Epaphrodites\database\query\Builders;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Epaphrodites\database\gearShift\databaseGearShift;
use Epaphrodites\epaphrodites\Console\Setting\settingMigration;

class modelMigration extends settingMigration{
 
    /**
    * @param \Symfony\Component\Console\Input\InputInterface $input
    * @param \Symfony\Component\Console\Output\OutputInterface $output
    */
    protected function execute( InputInterface $input, OutputInterface $output)
    {
        # Get console arguments
        $action = $input->getArgument('type');
       
        $db = max(1, (int) $this->shift()->db());
        
        $getQueryChaine = (string) $this->checkActionsType($action);

        if(!empty($getQueryChaine)){

            $this->executeQuery($getQueryChaine , $db);
            $output->writeln("<info>The migration has been successfully created!!!✅</info>");
            return self::SUCCESS;
        }else{
            $output->writeln("<error>Sorry, check your request before starting the migration ❌</error>");
            return self::FAILURE;
        }
    }

    /**
     * Check the type of migration action and get the corresponding query.
     * @param string $action
     * @return string
     */
    private function checkActionsType(string $action):string
    {
        $gearShift = $this->shift();

        return match ($action) 
        {
            'up' => $gearShift->up(),
            'down' => $gearShift->down(),
      
            default => '',
        };
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

   /**
     * Get an instance of the database gear shift.
     * @return databaseGearShift
    */    
    private function shift():databaseGearShift
    {
        return new databaseGearShift;
    }
    
}
        