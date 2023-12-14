<?php

namespace Epaphrodite\epaphrodite\Console\Models;

use Epaphrodite\epaphrodite\Console\Stubs\AddUserRightsStub;
use Symfony\Component\Console\Input\InputInterface;
use Epaphrodite\epaphrodite\Console\Setting\OutputDirectory;
use Symfony\Component\Console\Output\OutputInterface;
use Epaphrodite\epaphrodite\Console\Setting\AddRightsConfig;
use Epaphrodite\epaphrodite\EpaphMozart\ModulesConfig\Lists\GetRightList;
use Epaphrodite\epaphrodite\EpaphMozart\ModulesConfig\Lists\GetModulesList;

class AddUsersRights extends AddRightsConfig
{

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
    */   
    protected function execute( InputInterface $input, OutputInterface $output)
    {
        # Get console arguments
        $module = $input->getArgument('moduleKey');
        $path = $input->getArgument('path');
        $libelle = str_replace( '_' , ' ' , $input->getArgument('libelle'));
        
        # Get module list and verify key
        $ModuleList = GetModulesList::GetModuleList();
        $IfModuleKeyExist = array_key_exists($module,$ModuleList);    

        # Get Right and verify if key exist
        $IfPathExist = $this->CheckIntoRightList($path);

        # Get if main path exist
        $FileName = OutputDirectory::Files('rightlist') . '.php';

        if(file_exists($FileName)===true){

            if($IfModuleKeyExist===true){

                if($IfPathExist===false){
                    AddUserRightsStub::generate($FileName, $libelle , $path , $module);
                    $output->writeln("<info>The right has been successfully created!!!✅</info>");
            
                    return self::SUCCESS;
                }else{
                    $output->writeln("<error>Sorry, this link '{$path}' already exists.❌</error>");
                    return self::FAILURE;
                }
            }else{
                $output->writeln("<error>Sorry, this module '{$module}' already exists.❌</error>");
                return self::FAILURE;
            }
        }else{
            $output->writeln("<error>Sorry, the rights management file does not exist.❌</error>");
            return self::FAILURE;
        }
    }

    /**
     * @return bool
     */
    public function CheckIntoRightList($path){

        $result = false;

        # Get Right list and verify key
        $list = GetRightList::RightList();
        foreach ($list as $key => $value){
            $result = md5($value["path"])==md5($path) ? true : false ;
        }

        return $result;
    }
}