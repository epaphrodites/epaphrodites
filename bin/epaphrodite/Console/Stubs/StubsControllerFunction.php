<?php

 namespace Epaphrodite\epaphrodite\Console\Stubs;

 class StubsControllerFunction{
    
    public static function Generate($FilesNames, $name )
    {
       
        $stubs = static::stubs($name);

        $FilesContent = file_get_contents($FilesNames);
   
        $lastBracketPosition = strrpos($FilesContent, '}');
        if ($lastBracketPosition !== false) {
            $FilesContent = substr($FilesContent, 0, $lastBracketPosition);
        }  

        file_put_contents($FilesNames, $stubs."\n }" , FILE_APPEND | LOCK_EX);
    } 
    
    
    public static function stubs($name){

        $stub = 
        '
        /**
         * Action function name
         * 
         * @param string $html
         * @return mixed
         */
        '."public function $name".'(string $html): void{
    
        }';  
        
        return $stub;

    }
 }