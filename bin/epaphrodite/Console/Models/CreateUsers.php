<?php

namespace Epaphrodite\epaphrodite\Console\Models;

use Epaphrodite\epaphrodite\Console\Setting\UsersConfig;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Epaphrodite\database\requests\mainRequest\insert\insert as Insert;

class CreateUsers extends UsersConfig
{

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */    
    protected function execute( InputInterface $input, OutputInterface $output)
    {
        $username = $input->getArgument('username');
        $password = $input->getArgument('password');
        $UserGroup = $input->getArgument('userGroup');
        $result = (new Insert)->ConsoleAddUsers( $username , $password , $UserGroup );

        if($result===true){
            $output->writeln("<info>The {$username} has been successfully created!!!✅</info>");
            return self::SUCCESS;
        }else{
            $output->writeln("<error>The {$username} user already exists.❌</error>");
            return self::FAILURE;
        }
    }
}