<?php

namespace Epaphrodites\epaphrodites\env\config;

use Epaphrodites\database\datas\arrays\ApiStaticKeygen;

class GeneralConfig extends ApiStaticKeygen
{

    /**
     * @param mixed $Files
     * @param mixed $divid
     * @return string
     */
    public function EndFiles(
        $Files, 
        $divid
    )
    {

        $Files = explode($divid, $Files);

        return end($Files);
    }

    /**
     * @return string
     */
    public static function methods()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * @param string|null $key
     * @param string|null $values
     * @return void
     */
    public function SetSession(
        ?string $key = null, 
        ?string $values = null
    )
    {

        return $_SESSION[$key] = $values;
    }

    /**
     * @param string|null $key
     * @return void
     */
    public function GetSessions(
        ?string $key = null
    )
    {

        return isset($_SESSION[$key]) ? $_SESSION[$key] : NULL;
    }

    /**
     * @return string
     */
    public function GetFiles($key)
    {

        return $_FILES[array_keys($_FILES)[$key]]['tmp_name'];
    }

    public function FilesArray(): array
    {
        return array_keys($_FILES);
    }

    /**
     * Execute a Python script with given data.
     *
     * @param string|null $scriptPath - The path to the Python script.
     * @param array $data - Input data for the script.
     * @return string - Output of the Python script or an error message.
     */
    public function pythonSystemCode(
        ?string $scriptPath = null, 
        array $data = [] 
    ): string
    {

        if ($scriptPath === null || !file_exists($scriptPath)) {

            throw new \InvalidArgumentException("This file $scriptPath do not exist.");
        }

        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $data[$key] = str_replace(',', 'u7q7b;', $value);
            }
        }

        $escapedData = escapeshellarg(json_encode($data));

        $scriptPath = escapeshellcmd($scriptPath);

        $command = __PYTHON__." $scriptPath $escapedData";
        
        ob_start();

        passthru($command, $returnCode);

        $output = ob_get_clean();

        if ($returnCode !== 0) {

            throw new \RuntimeException("Error while executing the Python script. Return code : $returnCode");
        }

        return $output;
    } 
}