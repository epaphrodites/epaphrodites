<?php

namespace Epaphrodites\database\requests\typeRequest\sqlRequest\select;

use Epaphrodites\database\requests\typeRequest\noSqlRequest\select\get_id as SelectGet_id;

class get_id extends SelectGet_id
{

    /**
     * Request to get users by group
     *
     * @param integer $currentPage
     * @param integer $numLines
     * @param integer $usersGroup
     * @return array
     */
    public function sqlGetUsersByGroup(
        int $currentPage, 
        int $numLines, 
        int $usersGroup
    ):array
    {

        return match (_FIRST_DRIVER_) {

        'sqlserver' => $this->sqlServerGetUsersByGroup( $currentPage, $numLines, $usersGroup),

        default => $this->defaultSqlGetUsersByGroup( $currentPage, $numLines, $usersGroup)
        };
    }     

    /**
     * Request to get users by group
     *
     * @param integer $currentPage
     * @param integer $numLines
     * @param integer $usersGroup
     * @return array
     */
    public function defaultSqlGetUsersByGroup(
        int $currentPage, 
        int $numLines, 
        int $usersGroup
    ):array
    {

        $result = $this->table('useraccount')
            ->where('usersgroup')
            ->limit((($currentPage - 1) * $numLines), $numLines)
            ->orderBy('login', 'ASC')
            ->param([$usersGroup])
            ->SQuery();

        return $result;
    }

    /**
     * Request to get users by group
     *
     * @param integer $currentPage
     * @param integer $numLines
     * @param integer $usersGroup
     * @return array
     */
    public function sqlServerGetUsersByGroup(
        int $currentPage, 
        int $numLines, 
        int $usersGroup
    ):array
    {

        $result = $this->table('useraccount')
            ->where('usersgroup')
            ->offset((($currentPage - 1) * $numLines), $numLines)
            ->orderBy('login', 'ASC')
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
    public function sqlGetUsersDatas(
        ?string $login = null
    ):array
    {

        $login = static::initNamespace()['env']->no_space($login);

        $result = $this->table('useraccount')
            ->like('login')
            ->param([$login])
            ->SQuery();

        return $result;
    }

   /** 
     * Request to select users actions list by login
     * @param string|null $login
     * @return array
     */
    public function sqlGetUsersRecentsActions(
        ?string $login = null
    ):array
    {

        $login = static::initNamespace()['env']->no_space($login);

        $result = $this->table('history')
            ->like('actions')
            ->param([$login])
            ->SQuery();

        return $result;
    }    
}