<?php

namespace Epaphrodites\epaphrodites\Console\Models;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Epaphrodites\epaphrodites\Console\Stubs\stubChatbot;
use Epaphrodites\epaphrodites\Console\Setting\OutputDirectory;
use Epaphrodites\epaphrodites\Console\Setting\AddChatbotCommands;

class AddNewChatbot extends AddChatbotCommands{

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
    */
    protected function execute( InputInterface $input, OutputInterface $output)
    {
        # Get console arguments
        $chatBotName = $input->getArgument('name');
        $controller = $input->getArgument('controller');

        (string) $controller = empty($controller) ? 'chats' : $controller;

        $jsonPath = OutputDirectory::Files('json') . "/{$chatBotName}.json";
        $userJsonPath= OutputDirectory::Files('json') . "/user{$chatBotName}.json";
        $controller = OutputDirectory::Files('controlleur') . "/{$controller}.php";

        if(file_exists($jsonPath)===false&&file_exists($userJsonPath)===false){

            if(file_exists($controller)===true){

                stubChatbot::generate($jsonPath , $userJsonPath , $chatBotName, $controller);
                $output->writeln("<info>Your chatbot {$chatBotName} has been generated successfully!!!✅</info>");
                return self::SUCCESS;  
            }else{
                $output->writeln("<error>Sorry this controller '{$controller}' don't exist❌</error>");
                return self::FAILURE;
            }  

        }else{
            $output->writeln("<error>Sorry this chatbot '{$chatBotName}' exist❌</error>");
            return self::FAILURE;
        }
    }
}