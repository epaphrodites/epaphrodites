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
    public function __construct()
    {
        \$this->initializeObjects();
    }
        
    /**
     * Start exemple page
     * @param string \$html
     * @return void
    */      
    public function exemplePages(string \$html): void
    {
        //
    }
        
    /**
    * Get object
    * @return void
    */
    private function initializeObjects(): void
    {
        \$this->msg = \$this->getFunctionObject(static::initNamespace(), 'msg');
    }        
        
}";
        
    file_put_contents($FilesNames, $stub);
    }
}