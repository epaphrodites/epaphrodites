<?php

namespace Epaphrodite\epaphrodite\Console\Models;

use Epaphrodite\epaphrodite\Console\Stubs\RequestFilesStub;
use Symfony\Component\Console\Input\InputInterface;
use Epaphrodite\epaphrodite\Console\Setting\OutputDirectory;
use Symfony\Component\Console\Output\OutputInterface;
use Epaphrodite\epaphrodite\Console\Setting\RequestFileConfig;

class CreateRequestFiles extends RequestFileConfig
{

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
    */    
    protected function execute( InputInterface $input, OutputInterface $output)
    {
        $type = $input->getArgument('type');
        $name = $input->getArgument('name');
        if(is_dir(OutputDirectory::Files($type))!==false){
            $FileName = OutputDirectory::Files($type) . '/' . $name . '.php';
            RequestFilesStub::generate($FileName, $name , $type);
            $output->writeln("<info>The request file {$name} has been successfully created!!!</info>");
    
            return self::SUCCESS;
        }else{
            $output->writeln("<error>Sorry this request file {$type} already exists.</error>");
            return self::FAILURE;
        }
    }

}