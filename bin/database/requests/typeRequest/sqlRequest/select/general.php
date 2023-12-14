<?php

namespace Epaphrodite\database\requests\typeRequest\sqlRequest\select;

use Epaphrodite\database\requests\typeRequest\noSqlRequest\select\general as SelectGeneral;

class general extends SelectGeneral
{

    /**
     * Request to get six last actions
     * @return array
     */
    public function sqlRecentlyActions():array
    {

        $UserConnected = static::initNamespace()['session']->login();

        $result = $this->table('recentactions')
            ->like('usersactions')
            ->orderBy('idrecentactions', 'DESC')
            ->limit(0,6)
            ->param([$UserConnected])
            ->SQuery();

        return $result;
    }
}
