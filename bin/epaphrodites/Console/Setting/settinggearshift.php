<?php

namespace Epaphrodites\epaphrodites\Console\Setting;
        
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
        
class settinggearshift extends Command
{
        
    protected function configure()
    {
        $this->setDescription('Run the database migrations')
                ->setHelp('This is help.')
                ->addArgument('type', InputArgument::OPTIONAL, 'Migration type');
    }
}        
        