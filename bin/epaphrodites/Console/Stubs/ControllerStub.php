<?php

namespace Epaphrodites\epaphrodites\Console\Stubs;

class ControllerStub{

    public static function GenerateControlleurs($FilesNames, $name , $html='$html')
    {
$stub = 
"<?php
    namespace Epaphrodites\\controllers\\controllers;

    use Epaphrodites\\controllers\\switchers\\MainSwitchers;

    final class $name extends MainSwitchers
    {
        'public function exemplePages(string $html): void{
            //
        }
    }'";
    file_put_contents($FilesNames, $stub);
    }
}