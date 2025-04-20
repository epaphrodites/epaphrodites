<?php

namespace Epaphrodites\epaphrodites\Console\Models;
        
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Epaphrodites\epaphrodites\Console\Setting\settingaddCExtensions;
use Epaphrodites\epaphrodites\Console\Stubs\ExtensionBuilderService;

class modeladdCExtensions extends settingaddCExtensions{
        
        
    /**
    * @param \Symfony\Component\Console\Input\InputInterface $input
    * @param \Symfony\Component\Console\Output\OutputInterface $output
    */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $extensionName = $input->getArgument('name');
        $this->getExtensionName($extensionName, $output, new ExtensionBuilderService());

        return self::SUCCESS;
    }

    private function getExtensionName( $input, $output, $extensionBuilderService):void
    {
        $extensionBuilderService->build($input, $output);
    }
    
}
        