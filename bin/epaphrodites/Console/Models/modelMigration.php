<?php

namespace Epaphrodites\epaphrodites\Console\Models;
        
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

        $test = $this->checkActionsType($actionType);

        var_dump($test);die;
        // Your actions
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

}
        