<?php

namespace Epaphrodite\epaphrodite\Console\Models;

use Epaphrodite\epaphrodite\Console\Stubs\AllViewsStub;
use Symfony\Component\Console\Input\InputInterface;
use Epaphrodite\epaphrodite\Console\Setting\OutputDirectory;
use Symfony\Component\Console\Output\OutputInterface;
use Epaphrodite\epaphrodite\Console\Setting\CreateViewsConfig;

class CreatedViews extends CreateViewsConfig
{

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
    */    
    protected function execute( InputInterface $input, OutputInterface $output)
    {
        $directory = $input->getArgument('directoryGroup');
        $name = $input->getArgument('viewName');
        $locate = $input->getArgument('locate');
        $locate = $locate === NULL ? '': '/'.$locate;

        if(is_dir(OutputDirectory::Files($directory).$locate)!==false){
            
            $fileName = OutputDirectory::Files($directory) .$locate. '/' . $name . _MAIN_EXTENSION_ . _FRONT_;
            
            AllViewsStub::generate($fileName, $name , $directory);
            $output->writeln("<info>The view file {$name} has been successfully created!!!✅</info>");
    
            return self::SUCCESS;
        }else{
            $output->writeln("<error>Sorry, this view directory {$directory}{$locate} does not exist.❌</error>");
            return self::FAILURE;
        }
    }
}