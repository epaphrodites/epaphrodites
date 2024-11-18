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
    ): mixed {
        if ($scriptPath === null || !file_exists($scriptPath)) {
            throw new \InvalidArgumentException("This file $scriptPath does not exist.");
        }

        array_walk_recursive($data, function(&$value) {
            if (is_string($value)) {

                if ($this->isBinary($value)) {
                    $value = [
                        '_type' => 'binary',
                        'data' => base64_encode($value)
                    ];
                }
            }
        });

        $encodedData = base64_encode(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        $scriptPath = escapeshellarg($scriptPath);

        $command = _PYTHON_ . " " . $scriptPath . " " . escapeshellarg($encodedData) . " 2>&1";

        $descriptorSpec = [
            0 => ["pipe", "r"],
            1 => ["pipe", "w"],
            2 => ["pipe", "w"]
        ];

        $process = proc_open($command, $descriptorSpec, $pipes);

        if (!is_resource($process)) {
            throw new \RuntimeException("Failed to start Python process");
        }

        fclose($pipes[0]);

        $output = stream_get_contents($pipes[1]);
        $errors = stream_get_contents($pipes[2]);

        fclose($pipes[1]);
        fclose($pipes[2]);

        $returnCode = proc_close($process);

        if ($returnCode !== 0) {
            throw new \RuntimeException(
                "Error executing Python script. Return code: $returnCode\nErrors: $errors"
            );
        }

        if ($this->isBase64($output)) {
            $output = base64_decode($output);
        }

        return trim($output);
    }

    private function isBinary(string $str): bool {
        return preg_match('~[^\x20-\x7E\t\r\n]~', $str) > 0;
    }

    private function isBase64(string $str): bool {
        return base64_encode(base64_decode($str, true)) === $str;
    }
}