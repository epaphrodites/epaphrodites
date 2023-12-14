<?php
namespace Epaphrodite\epaphrodite\Console\Models;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Epaphrodite\database\config\process\checkDatabase;
use Epaphrodite\epaphrodite\Console\Setting\AddNewDatabase;

class createNewDatabase extends AddNewDatabase{

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
    */
    protected function execute( InputInterface $input, OutputInterface $output)
    {
        # Get console arguments
        $database = $input->getArgument('datatase');
        $order = $input->getArgument('order') ? $input->getArgument('order') : 1;

        $result = (new checkDatabase)->etablishConnect($database , $order );

        if( $result == true ){

            $output->writeln("<info>Your database {$database} has been created successfully!!!✅</info>");
            return self::SUCCESS;            

        }else{
            $output->writeln("<error>Please check your configuration or the existence of this database {$database} ❌</error>");
            return self::FAILURE;
        }
    }
}