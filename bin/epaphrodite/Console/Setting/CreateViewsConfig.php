<?php

namespace Epaphrodite\epaphrodite\Console\Setting;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;

class CreateViewsConfig extends Command
{

    protected function configure()
    {
        $this->setDescription('Create a front views')
             ->setHelp('This command allows you to create a new view.')
             ->addArgument('directoryGroup', InputArgument::REQUIRED, 'Directory group admin/main')
             ->addArgument('viewName', InputArgument::REQUIRED, 'Name of view')
             ->addArgument('locate', InputArgument::OPTIONAL, 'Locate folder target');
    }
}