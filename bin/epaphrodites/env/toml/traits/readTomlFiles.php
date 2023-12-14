<?php

namespace Epaphrodites\epaphrodites\env\toml\traits;

trait readTomlFiles
{
    private array $param = [];
    private string $section = '';
    private array $tomlData = [];

    /**
     * Reads data from the TOML file.
     * 
     * @param int|null $file Optional file parameter
     * @return array
     */
    private function read(?int $file = 1): array
    {

        $noellaTomlDatas = [];

        $tomlFilePath = $this->loadTomlFile($file);

        try {
            if (!file_exists($tomlFilePath)) {
                throw new \RuntimeException("TOML file '$tomlFilePath' not found.");
            }

            $noellaTomlDatas = $this->readTomlFile($tomlFilePath);
        } catch (\Exception $e) {
            throw new \RuntimeException("Failed to read TOML file: " . $e->getMessage(), 0, $e);
        }

        return $noellaTomlDatas;
    }

    /**
     * Sets the section/table name for TOML data retrieval within the instance.
     *
     * @param string $section The name of the section/table in the TOML data
     * @return self Returns the current instance of the class with the specified section set
     */
    private function setSection(string $section): self
    {
        $this->section = $section;

        return $this;
    }


    /**
     * Sets the section/table name for TOML data retrieval.
     *
     * @param string $section The name of the section/table in the TOML data
     * @return self Returns an instance of the class with the specified section set
     */
    public static function section(string $section): self
    {
        return (new self())->setSection($section);
    }

    /**
     * Specifies multiple parameters to retrieve from the table.
     * 
     * @param array $params Array of parameter names
     * @return self
     */
    public function param(array $params): self
    {

        $this->param = is_array($params) ? $params : [$params];
        
        return $this;
    }

    /**
     * Retrieves either specific elements from the table or the entire table.
     * 
     * @return array|null Either an associative array of specified elements or a single element
     */
    public function get(?int $file = 1): array|null
    {

        $this->tomlData = $this->read($file);

        if (!isset($this->tomlData[$this->section])) {
            return null;
        }

        if (is_array($this->param) && !empty($this->param)) {

            $result = [];
            foreach ($this->param as $param) {

                $result[$param] = $this->tomlData[$this->section][$param] ?? null;
            }
            return $result;
        }

        return $this->tomlData[$this->section];
    }
}
