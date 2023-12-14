<?php

namespace Epaphrodites\epaphrodites\env\toml\traits;

use ErrorException;

trait loadTomlFile
{

    /**
     * Reads content from the TOML file.
     *
     * @param int|null $file Filename without extension
     * @return array|null Content of the TOML file
     */
    public function readTomlFile($file): array|null
    {

        $fileName = $this->loadTomlFile($file);

        return file_exists($fileName) ? parse_ini_file($fileName, true) : NULL;
    }

    /**
     * Reads content from the TOML file.
     *
     * @param int|null $file Filename without extension
     * @return string|null Content of the TOML file
     */
    public function parseTomlFile($file): string|null
    {

        $fileName = $this->loadTomlFile($file);

        return file_exists($fileName) ? file_get_contents($fileName) : NULL;
    }

    /**
     * Writes content to the TOML file.
     *
     * @param int|null $file Filename without extension
     * @param string $content Content to write to the file
     * @return void
     */
    public function writeTomlFile(?int $file, string $content): void
    {

        $filePath = $this->loadTomlFile($file);

        file_exists($filePath) ? file_put_contents($filePath, $content) : NULL;
    }

    /**
     * Generates the path to the TOML file based on the given filename.
     * 
     * @param string|null $file Filename without extension
     * @return string Full path to the TOML file
     */
    public function loadTomlFile(?string $file): string
    {
        if ($file === null) {
            throw new \InvalidArgumentException('Filename cannot be null.');
        }

        $filePath = _DIR_TOML_DATAS_ . "{$file}_tomlDatas.toml";

        if (!is_readable($filePath)) {
            throw new ErrorException(sprintf('File "%s" is not readable', $filePath));
        }

        if (!is_file($filePath)) {
            throw new ErrorException(sprintf('File "%s" does not exist.', $filePath));
        }

        return $filePath;
    }
}
