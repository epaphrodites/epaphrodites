<?php

 namespace Epaphrodites\epaphrodites\Console\Stubs;

 class stubChatbot{
    
    public static function Generate(string $jsonPath , string $userJsonPath, string $chatBotName , string $controller = "main" )
    {
       
        $stubs = static::stubs($chatBotName);

        $jsonStubs = static::JsonContentModel($chatBotName);

        $FilesContent = file_get_contents($controller);
   
        $lastBracketPosition = strrpos($FilesContent, '}');

        if ($lastBracketPosition !== false) {
            $FilesContent = substr($FilesContent, 0, $lastBracketPosition);
        }  

        if ($lastBracketPosition !== false) {

            $FilesContent = substr_replace($FilesContent, $stubs."\n}", $lastBracketPosition);
            file_put_contents($controller, $FilesContent, LOCK_EX);
            file_put_contents($jsonPath, json_encode($jsonStubs,JSON_PRETTY_PRINT));
            file_put_contents($userJsonPath, json_encode([],JSON_PRETTY_PRINT));
        }
    } 
    
    
    private static function stubs($chatBotName){

        $functionName = static::transformToFunction($chatBotName);

        $stub = 
        "
    /**
    * start {$chatBotName} chatBot
    * 
    * @param string \$html
    * @return void
    */
    public final function {$functionName}Started(string \$html): void
    {
        \$botResponse = '';

        \$chatBotName='$chatBotName';

        if (static::isValidMethod()) {

            \$send = static::isPost('__send__') ? static::isPost('__send__') : '';
    
            \$result = \$this->initNamespace()['bot']->herediaBot(\$send , \$chatBotName);
    
            \$botResponse = \$this->initNamespace()['ajax']->chatMessageContent(\$result , \$send , \$chatBotName);
        }

        static::rooter()->target(_DIR_MAIN_TEMP_ . \$html)->content([ 'botResponse' => \$botResponse ])->get();
    }";  
        
        return $stub;
    }

    /**
     * @param string $chatBotName
     * @return array
     */
    private static function JsonContentModel(string $chatBotName):array
    {

        return [
            'hello hi' => [
                "answers" => ["I am {$chatBotName}, your AI assistant."],
                "type" => "txt"
            ]
        ];

    }

    /**
     *  @param string $initPage
     * @return string
     */
    private static function transformToFunction(string $initPage): string
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