<?php

namespace Epaphrodites\epaphrodites\env\toml\traits;

trait delToTomlFile
{

    public function del(?int $file = 1) {

        $contenu = $this->parseTomlFile($file);

        if ($this->section !== null) {
            $pattern = '/\[' . $this->section . '\]\s*(.*?)\n(\[\[.*?\]\]|\[.*?\]|$)/s';

            preg_match($pattern, $contenu, $matches);

            if (isset($matches[1])) {
                $sectionContent = $matches[1];

                foreach ($this->param as $prop) {
                    $contenu = preg_replace('/' . $prop . '\s*=\s*.*(\n|$)/', '', $sectionContent);
                }
            }
        }

        $this->writeTomlFile( $file, $contenu);
    }

    
    
}