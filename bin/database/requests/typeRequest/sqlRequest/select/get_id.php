<?php

namespace Epaphrodites\database\requests\typeRequest\sqlRequest\select;

use Epaphrodites\database\requests\typeRequest\noSqlRequest\select\get_id as SelectGet_id;

class get_id extends SelectGet_id
{

    /**
     * Request to get users by group
     *
     * @param integer $page
     * @param integer $numLines
     * @param integer $usersGroup
     * @return array
     */
    public function sqlGetUsersByGroup(int $page, int $numLines, int $usersGroup):array
    {

        $result = $this->table('useraccount')
            ->where('usersgroup')
            ->limit((($page - 1) * $numLines), $numLines)
            ->orderBy('loginusers', 'ASC')
            ->param([$usersGroup])
            ->SQuery();

        return $result;
    }

    /** 
     * Request to select users by login
     *
     * @param string|null $login
     * @return array
     */
    public function sqlGetUsersDatas(?string $login = null):array
    {

        $login = static::initNamespace()['env']->no_space($login);

        $result = $this->table('useraccount')
            ->like('loginusers')
            ->param([$login])
            ->SQuery();

        return $result;
    }

   /** 
     * Request to select users actions list by login
     * @param string|null $login
     * @return array
     */
    public function sqlGetUsersRecentsActions(?string $login = null):array
    {

        $login = static::initNamespace()['env']->no_space($login);

        $result = $this->table('recentactions')
            ->like('usersactions')
            ->param([$login])
            ->SQuery();

        return $result;
    }    
}
