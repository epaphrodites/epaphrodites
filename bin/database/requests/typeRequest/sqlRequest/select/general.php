<?php

namespace Epaphrodites\database\requests\typeRequest\sqlRequest\select;

use Epaphrodites\database\requests\typeRequest\noSqlRequest\select\general as SelectGeneral;

class general extends SelectGeneral
{

    /**
     * Request to get users recents actions
     * @return array
     */
    public function sqlRecentlyActions():array
    {

        return match (_FIRST_DRIVER_) {

        'sqlserver' => $this->sqlServerRecentActions(),

        default => $this->defaultSqlServerRecentActions(),
        };
    }    

    /**
     * Request to get six last actions
     * @return array
     */
    public function sqlServerRecentActions():array
    {
       
        $UserConnected = static::initNamespace()['session']->login();

        $result = $this->table('recentactions')
            ->like('usersactions')
            ->orderBy('idrecentactions', 'DESC')
            ->offset(0,6)
            ->param([$UserConnected])
            ->SQuery();
       
        return $result;
    }

    /**
     * Request to get six last actions
     * @return array
     */
    public function defaultSqlServerRecentActions():array
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
