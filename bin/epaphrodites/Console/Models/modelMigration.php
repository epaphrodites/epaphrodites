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
        $actionType = $input->getArgument('type');

        $getQueryChaine = (string) $this->checkActionsType($actionType);

        if(!empty($getQueryChaine)){

            $this->executeQuery($getQueryChaine);
            $output->writeln("<info>The migration has been successfully created!!!✅</info>");
            return self::SUCCESS;
        }else{
            $output->writeln("<error>Sorry, check your request before starting the migration ❌</error>");
            return self::FAILURE;
        }
    }

    private function checkActionsType(string $action)
    {
        $gearShift = new databaseGearShift;

        return match ($action) {

            'up' => $gearShift->up(),
            'down' => $gearShift->down(),
      
            default => $gearShift->up(),
          };
    }

    private function executeQuery(string $queryChaine){

        $database = new Builders;

        $database->chaine($queryChaine)->setQuery();
    }

}
        