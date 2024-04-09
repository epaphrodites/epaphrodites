<?php

namespace Epaphrodites\epaphrodites\env\toml\requests;

trait getToml
{
 
    /**
     * @param array $datas
     * @return array
     */
    public function get(array $datas = []):array
    {
        $tomlFileDatas = $this->loadTomlFile($this->path);

        $tomlFileDatas = $this->translateToArray($tomlFileDatas);

        $tomlFileDatas = $this->section ? $tomlFileDatas[$this->section] : $tomlFileDatas;

        if (empty($datas)) {
            return $tomlFileDatas;
        }
    
        return array_filter($tomlFileDatas, function ($value) use ($datas) {
            foreach ($datas as $key => $val) {
                if (!isset($value[$key]) || $value[$key] !== $val) {
                    return false;
                }
            }
            return true;
        });
    }

    /**
     * @param string $content
     * @return array
     */
    private function translateToArray(string $content):array{

        $config_data = [];

        $lines = explode("\n", $content);
        $current_table = null;
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line) || $line[0] === "#") {
                continue;
            }
            if ($line[0] === "[") {
                $current_table = trim($line, "[]");
                $config_data[$current_table] = [];
            } else {
                list($key, $value) = explode("=", $line, 2);
                $key = trim($key);
                $value = trim($value);
                $value = trim($value, "\"'");
                $config_data[$current_table][$key] = $value;
            }
        }

        return $config_data;
    }
}