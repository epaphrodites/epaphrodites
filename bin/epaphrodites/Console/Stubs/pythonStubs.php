<?php

 namespace Epaphrodites\epaphrodites\Console\Stubs;

 class pythonStubs{

    /**
     * @param string $FilesNames
     * @param string $functionName
     * @return void
     */
    public static function Generate(string $FilesNames , string $functionName , string $fileInit):void
    {
       
       $stubs = static::stubs($functionName);
        static::AddToConfig($fileInit,$functionName);

        file_put_contents($FilesNames, $stubs);
    } 

    /**
     * @param string $functionName
     * @return string
     */
    public static function stubs(string $functionName):string
    {

        $stub = 
        "import sys

class {$functionName}:

    def print_hello_world(self):
        print('Hello, World!')

if __name__ == '__main__':  
    hello_world_instance = {$functionName}()
    hello_world_instance.print_hello_world()  
        ";  
        
        return $stub;
    }

    public static function addToConfig(string $fileName, string $functionName): bool{

        $jsonConfigContent = file_get_contents(static::loadJsonConfig());

        $newJsonData = [
                "script" => "{$fileName}.py",
                "function" => $functionName
            ];

        $jsonConfigArray = json_decode($jsonConfigContent, true);

        $jsonConfigArray[$functionName] = $newJsonData;

        $jsonContent = json_encode($jsonConfigArray, JSON_PRETTY_PRINT);

        file_put_contents(static::loadJsonConfig(), $jsonContent);

        return true;
    }


    /**
     * Get JSON content from the config file.
     * @return string
     */
    private static function loadJsonConfig():string
    {
        $getFiles = _PYTHON_ . 'config/config.json';

        return $getFiles;
    }    

 }