<?php

namespace Epaphrodites\epaphrodites\Console\Models;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Epaphrodites\epaphrodites\Console\Stubs\migrationStubs;
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
       
        $results = $this->setUsersRequest($action);

        if($results === true ){

            $output->writeln("<info>The migration has been successfully created!!!✅</info>");
            return self::SUCCESS;
        }else{
            $output->writeln("<error>Sorry, check your request before starting the migration ❌</error>");
            return self::FAILURE;
        }
    }

    private function setUsersRequest(string $actions):bool
    {
        $result = explode( '_' , $actions);
 
        if(count($result)>=3){

            $type = end($result);
            $action = reset($result);
            $tableName = implode('_', array_slice($result, 1, -1));

            if($action === "create" && $type === "table"){

                migrationStubs::generateMigration( $actions , $tableName);
                return true;
            }

            if($action === "drop" && $type === "table"){

                migrationStubs::dropMigration( $actions , $tableName);
                return true;
            }            
        }
        
        return false;
    }  
}
        