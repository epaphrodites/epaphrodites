<?php

namespace Epaphrodite\epaphrodite\Console\Stubs;

class ControllerStub{

    public static function GenerateControlleurs($FilesNames, $name , $html='$html')
    {
$stub = 
"<?php
    namespace Epaphrodite\\controllers\\controllers;

    use Epaphrodite\\controllers\\switchers\\MainSwitchers;

    final class $name extends MainSwitchers
    {
        'public function exemplePages(string $html): void{
            //
        }
    }'";
    file_put_contents($FilesNames, $stub);
    }
}