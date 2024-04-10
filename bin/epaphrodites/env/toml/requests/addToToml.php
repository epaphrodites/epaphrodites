<?php

namespace Epaphrodites\epaphrodites\env\toml\requests;

trait addToToml
{

    private $path;
    private $section;
    private string $mergeDatas = '';

    /**
     * @param string $path
     * @return self
     */
    public function path(
        string $path
    ):self{

        $this->path = $path;
        return $this;
    }

    /**
     * @param string $section
     * @return self
    */
    public function section(
        string $section
    ): self{

        $this->section = $section;
        return $this;
    } 
    
    /**
     * @param array $datas
     * @return bool
     */
    public function add( array $datas = []): bool
    {

        $content = $this->parsToml($this->path);

        $tomlDatas = $this->loadTomlFile($this->path);

        $currentDatas = !empty($tomlDatas[$this->section]) ? true : false;

        if ($currentDatas == false) {

            $this->mergeDatas .= "[$this->section]\n";

            foreach ($datas as $key => $value) {

                if (is_string($value)) {
                    $this->mergeDatas .= "$key = \"$value\"\n";
                } else {
                    $this->mergeDatas .= "$key = $value\n";
                }
            }
            
            $content .= !empty($content) ? "\n$this->mergeDatas" : "$this->mergeDatas";

            $tomlDatas .= "$content\n";
    
            $this->saveToml($this->path, $content);    
            
            return true;
        }

        return false;
    }
}