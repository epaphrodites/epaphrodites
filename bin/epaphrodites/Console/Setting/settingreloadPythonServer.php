<?php

namespace Epaphrodites\epaphrodites\Console\Setting;
        
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
        
class settingreloadPythonServer extends Command
{
        
    protected function configure()
    {
        $this->setDescription('Add your command description')
                ->setHelp('This is help.')
                ->addOption('r', 'r', InputOption::VALUE_NONE, 'Up migration');
    }
}        
        