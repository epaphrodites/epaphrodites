<?php

namespace Epaphrodite\epaphrodite\Console\Setting;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;

class RequestFileConfig extends Command
{

    protected function configure()
    {
        $this->setDescription('Create a new Request file')
             ->setHelp('This command allows you to create a new Request file.')
             ->addArgument('type', InputArgument::REQUIRED, 'Type of the Request file')
             ->addArgument('name', InputArgument::REQUIRED, 'Name of the Request file');
    }
}