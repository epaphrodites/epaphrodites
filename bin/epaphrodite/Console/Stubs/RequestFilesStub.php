<?php

namespace Epaphrodite\epaphrodite\Console\Stubs;

class RequestFilesStub extends SqlStub{

public static function generate($FilesNames, $name , $type)
{
    $content = static::SwicthRequestContent($type,$name);
    
$stub = 
"<?php
    namespace Epaphrodite\\database\\requests\\mainRequest\\$type;

    use Epaphrodite\\database\\requests\\typeRequest\\sqlRequest\\$type\\$type as $type$type;

    class {$name} extends $type$type
    {

        $content

    }";
    
    file_put_contents($FilesNames, $stub);
    }
}