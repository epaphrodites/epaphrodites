<?php

namespace Epaphrodites\epaphrodites\Console\Setting;
        
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
        
class settinggearshift extends Command
{
        
    protected function configure()
    {
        $this->setDescription('Add your command description')
                ->setHelp('This is help.')
                ->addArgument('name', InputArgument::OPTIONAL, 'Your argument name');
    }
}        
        