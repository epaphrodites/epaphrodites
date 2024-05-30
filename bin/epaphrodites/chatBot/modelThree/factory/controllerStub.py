import sys
sys.path.append('bin/epaphrodites/chatBot/mainConfig/')
from constants import _CONTROLLER_PATH_

class controllerStub:
    
    def __init__(self, file_name, class_name):
        self.file_name = file_name
        self.class_name = class_name

    def generate_stub(self):
        stub = f"""<?php
namespace Epaphrodites\\controllers\\controllers;
        
use Epaphrodites\\controllers\\switchers\\MainSwitchers;
        
final class {self.class_name} extends MainSwitchers
{{
    private object $msg;

    /**
    * Initialize object properties when an instance is created
    * @return void
    */    
    public final function __construct()
    {{
        $this->initializeObjects();
    }}

    /**
    * Initialize each property using values retrieved from static configurations
    * @return void
    */
    private function initializeObjects(): void
    {{
        $this->msg = $this->getFunctionObject(static::initNamespace(), 'msg');
    }}       
        
    /**
     * Start exemple page
     * @param string $html
     * @return void
    */      
    public final function exemplePages(string $html): void
    {{
        $this->views( $html, [], false );
    }}     
        
}}"""

        with open( _CONTROLLER_PATH_ + self.file_name, "w") as file:
            file.write(stub)
            
            #PhpStubGenerator(file_name, class_name).generate_stub()