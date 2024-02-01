<?php

namespace Epaphrodites\epaphrodites\chatBot\botConfig;

trait languageDetection
{

    /**
     * @param string $login
     * @param string $jsonFiles
     * @return string
     */
    private function detectLastLang(string $login , string $jsonFiles = 'BotSession'):string
    {
        $jsonDatas = $this->loadJsonFile('user'.$jsonFiles);

        for ($i = count($jsonDatas) - 1; $i >= 0; $i--) {
            $value = $jsonDatas[$i];
            if ($value['login'] === $login) {
                return $value['language'];
            }
        }

        return "eng";
    }
}