<?php

 namespace Epaphrodites\epaphrodites\Console\Stubs;

 class StubsControllerFunction{
    
    /**
     * @param string $FilesNames
     * @param string $name
     * @return void
     */
    public static function Generate(string $FilesNames, string $name ):void
    {
       
        $stubs = static::stubs($name);

        $FilesContent = file_get_contents($FilesNames);
   
        $lastBracketPosition = strrpos($FilesContent, '}');

        if ($lastBracketPosition !== false) {
            $FilesContent = substr($FilesContent, 0, $lastBracketPosition);
        }  

        if ($lastBracketPosition !== false) {

            $FilesContent = substr_replace($FilesContent, $stubs."\n}", $lastBracketPosition);
            file_put_contents($FilesNames, $FilesContent, LOCK_EX);
        }
    } 
    
    /**
     * @param string $initPage
     * @return string
     */
    public static function stubs(string $initPage):string
    {

        $functionName = static::transformToFunction($initPage);

        $stub = 
        "
        /**
         * start view function
         * 
         * @param string \$html
         * @return void
         */
        public final function {$functionName}(string \$html): void{
    
        }";  
        
        return $stub;
    }

    /**
     *  @param string $initPage
     * @return string
     */
    private static function transformToFunction($initPage): string
    {

        $parts = explode('_', $initPage);

        $camelCaseParts = array_map(function ($part) {
            return ucfirst($part);
        }, $parts);

        $camelCaseString = lcfirst(implode('', $camelCaseParts));

        $contract = explode('/', $camelCaseString);

        $parts = count($contract) > 1 ? $contract[1] : $contract[0];

        return $parts;
    }    
 }