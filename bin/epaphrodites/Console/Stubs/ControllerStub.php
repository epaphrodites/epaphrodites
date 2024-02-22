<?php

namespace Epaphrodites\epaphrodites\Console\Stubs;

class ControllerStub{

    public static function GenerateControlleurs($FilesNames, $name)
    {
$stub = "<?php
namespace Epaphrodites\\controllers\\controllers;
        
use Epaphrodites\\controllers\\switchers\\MainSwitchers;
        
final class $name extends MainSwitchers
{
    private object \$msg;

    /**
    * Initialize object properties when an instance is created
    * @return void
    */    
    public final function __construct()
    {
        \$this->initializeObjects();
    }

    /**
    * Initialize each property using values retrieved from static configurations
    * @return void
    */
    private function initializeObjects(): void
    {
        \$this->msg = \$this->getFunctionObject(static::initNamespace(), 'msg');
    }       
        
    /**
     * Start exemple page
     * @param string \$html
     * @return void
    */      
    public final function exemplePages(string \$html): void
    {
        \$this->views( \$html, [], false );
    }     
        
}";
        
    file_put_contents($FilesNames, $stub);
    }
}