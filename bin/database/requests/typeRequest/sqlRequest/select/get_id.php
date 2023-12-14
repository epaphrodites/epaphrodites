<?php

namespace Epaphrodite\database\requests\typeRequest\sqlRequest\select;

use Epaphrodite\database\requests\typeRequest\noSqlRequest\select\get_id as SelectGet_id;

class get_id extends SelectGet_id
{

    /**
     * Request to get users by group
     *
     * @param integer $page
     * @param integer $Nbreligne
     * @param integer $UsersGroup
     * @return array
     */
    public function sqlGetUsersByGroup(int $page, int $Nbreligne, int $UsersGroup):array
    {

        $result = $this->table('useraccount')
            ->where('typeusers')
            ->limit((($page - 1) * $Nbreligne), $Nbreligne)
            ->orderby('loginusers', 'ASC')
            ->param([$UsersGroup])
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
