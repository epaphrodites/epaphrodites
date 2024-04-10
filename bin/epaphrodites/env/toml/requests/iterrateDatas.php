<?php

namespace Epaphrodites\epaphrodites\env\toml\requests;

trait iterrateDatas
{

    /**
     * @param array $array
     * @param int $indent
     * @return string
     */
    private function translateToToml(
        array $array, 
        int $indent = 0
    ):string {
        $toml = "";

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $toml .= str_repeat("", $indent) . "[" . $key . "]\n";
                $toml .= $this->translateToToml($value, $indent + 2);
            } else {
                $toml .= str_repeat("", $indent) . $key . " = \"" . $value . "\"\n";
            }
        }

        return $toml;
    }
    
    /**
     * @param string $content
     * @return array
     */
    private function translateToArray(
        string $content
    ):array{
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
    
    private function filterArrayDatas(
        array $initDatas, 
        array $search 
    ):array{

        return array_filter($initDatas, function ($value) use ($search) {

            foreach ($search as $key => $val) {
                if (!isset($value[$key]) || $value[$key] !== $val) {
                    return false;
                }
            }
            return true;
        });
    }
}