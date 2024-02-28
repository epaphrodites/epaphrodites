<?php

namespace Epaphrodites\epaphrodites\Console\Models;

use Epaphrodites\epaphrodites\Console\Stubs\RequestFilesStub;
use Symfony\Component\Console\Input\InputInterface;
use Epaphrodites\epaphrodites\Console\Setting\OutputDirectory;
use Symfony\Component\Console\Output\OutputInterface;
use Epaphrodites\epaphrodites\Console\Setting\RequestFileConfig;

class CreateRequestFiles extends RequestFileConfig
{

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
    */    
    protected function execute( InputInterface $input, OutputInterface $output)
    {
        $type = $input->getArgument('type');
        $requestFile = $input->getArgument('file');
        $name = $input->getArgument('name');

        $fileType = $type==="sql" ? "sqlRequest/":"noSqlRequest/";
        $FileName = OutputDirectory::Files('request') . "/{$fileType}{$requestFile}/{$name}.php";

        if(is_dir(OutputDirectory::Files('request'))."/{$fileType}"!==true){

            if(file_exists($FileName)===false){
     
                RequestFilesStub::generate($FileName, $name , $type);
                $output->writeln("<info>The request file {$name} has been successfully created!!!</info>");
        
                return self::SUCCESS;
            }else{
                $output->writeln("<error>Sorry this request file {$type} already exists.</error>");
                return self::FAILURE;
            }
        }else{
            $output->writeln("<error>Sorry this request directory {$fileType} don't already.</error>");
            return self::FAILURE;
        }
    }

}