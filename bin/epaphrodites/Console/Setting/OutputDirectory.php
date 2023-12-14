<?php

namespace Epaphrodites\epaphrodites\Console\Setting;

class OutputDirectory
{

    /**
     * @param null|string $Key
     * @return string
     */
    public static function Files(?string $Key = null)
    {
        $result = false;

        $list = [
            'main' => 'bin/views/main',
            'admin' => 'bin/views/admin',
            'controlleur' => 'bin/controllers/controllers',
            'count' => 'bin/database/requests/mainRequest/count',
            'insert' => 'bin/database/requests/mainRequest/insert',
            'update' => 'bin/database/requests/mainRequest/update',
            'delete' => 'bin/database/requests/mainRequest/delete',
            'select' => 'bin/database/requests/mainRequest/select',
            'rightlist' => 'bin/epaphrodite/EpaphMozart/ModulesConfig/Lists/GetRightList',
            'modulelist' => 'bin/epaphrodite/EpaphMozart/ModulesConfig/Lists/GetModulesList',
        ];

        foreach ($list as $ListKey => $value) {
            if ($ListKey == $Key) {
                $result = $value;
            }
        }
        return $result;
    }
}
