<?php

namespace Epaphrodites\epaphrodites\env\toml\saveLoad;

use ErrorException;

trait saveTomlDatas
{

    /**
     * Writes content to the TOML file.
     *
     * @param int|null $path Filename without extension
     * @param string $content Content to write to the file
     * @return bool
     */
    public function saveToml(
        string $path, 
        string $content
    ): bool{

        $tomlDatas = $this->loadTomlFile($path);

        $tomlDatas .= "$content\n";

        file_exists($path) ? file_put_contents($path, $tomlDatas) : NULL;
        
        return true;
    }  
}