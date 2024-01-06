<?php

 namespace Epaphrodites\epaphrodites\Console\Stubs;

 class stubChatbot{
    
    public static function Generate(string $FilesNames, string $chatBotName , string $controller = "main" )
    {
       
        $stubs = static::stubs($chatBotName);

        $FilesContent = file_get_contents($controller);
   
        $lastBracketPosition = strrpos($FilesContent, '}');

        if ($lastBracketPosition !== false) {
            $FilesContent = substr($FilesContent, 0, $lastBracketPosition);
        }  

        if ($lastBracketPosition !== false) {

            $FilesContent = substr_replace($FilesContent, $stubs."\n}", $lastBracketPosition);
            file_put_contents($FilesNames, $FilesContent, LOCK_EX);
        }
    } 
    
    
    public static function stubs($chatBotName){

        $functionName = static::transformToFunction($chatBotName);

        $stub = 
        "
        /**
         * start view function
         * 
         * @param string \$html
         * @return void
         */
        public final function {$functionName}(string \$html): void{
    
            \$chatBotName='$chatBotName';

            if (static::isValidMethod()) {

                \$send = static::isAjax('__send__') ? static::isAjax('__send__') : '';
    
                \$this->result = \$this->chatBot->herediaBot(\$send , \$chatBotName);
    
                echo \$this->ajaxTemplate->chatMessageContent(\$this->result , \$chatBotName);
               
                return;
            }
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