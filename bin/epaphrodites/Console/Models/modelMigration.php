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

        $getQueryChaine = $this->checkActionsType($actionType);

        $this->executeQuery($getQueryChaine);

        $output->writeln("<info>The migration has been successfully created!!!✅</info>");
            return self::SUCCESS;

    }


    public function checkActionsType(string $action):string
    {
        $gearShift = new databaseGearShift;

        return match ($action) {

            'upd' => $gearShift->addGearShift(),
            'add' => $gearShift->addGearShift(),
            'drop' => $gearShift->addGearShift(),
      
            default => $gearShift->addGearShift(),
          };
    }

    private function executeQuery(string $queryChaine){

        $database = new Builders;

        $database->chaine($queryChaine)->setQuery();
    }

}
        