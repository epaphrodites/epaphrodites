<?php

declare(strict_types=1);

namespace Epaphrodites\epaphrodites\translate;

use Epaphrodites\epaphrodites\env\config\GeneralConfig;

class PythonCodesTranslate extends GeneralConfig
{
    
    /**
     * Execute Python script.
     *
     * @param string|null $pyFunction
     * @param array $datas
     * @return mixed
     */
    public function executePython(
        ?string $pyFunction = null, 
        array $datas = []
    ):mixed{
        $getJsonContent = $this->loadJsonConfig();

        if (!empty($getJsonContent[$pyFunction])) {

            $scriptInfo = $getJsonContent[$pyFunction];

            $mergedDatas = array_merge(['function' => $scriptInfo["function"]], $datas);

            return $this->pythonSystemCode(_PYTHON_FILE_FOLDERS_ . $scriptInfo["script"], $mergedDatas);
        } else {
            return false;
        }
    }

    /**
     * Get JSON content from the config file.
     * @return array
     */
    private function loadJsonConfig(): array
    {
        $getFiles = _PYTHON_FILE_FOLDERS_ . 'config/config.json';

        return json_decode(file_get_contents($getFiles), true);
    }
}