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
      
        $gearDatabase = new databaseGearShift;

        $test = $gearDatabase->addGearShift();

        # Get console arguments
        $name = $input->getArgument('name');

        var_dump($test);die;
        // Your actions
    }

}
        