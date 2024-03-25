<?php

namespace Epaphrodites\database\requests\typeRequest\noSqlRequest\select;

use Epaphrodites\database\query\Builders;

class get_id extends Builders
{

    /**
     * Get users list
     *
     * @param integer $currentPage
     * @param integer $numLines
     * @param integer $usersGroup
     * @return array
     */
    public function noSqlGetUsersByGroup(
        int $currentPage, 
        int $numLines, 
        int $usersGroup
    ):array
    {

        $documents = [];

        $result = $this->db(1)
            ->selectCollection('useraccount')
            ->find(['usersgroup' => $usersGroup], [
                'limit' => $numLines, 'skip' => ($currentPage-1),
            ]);

        foreach ($result as $document) {
            $documents[] = $document;
        }
        return $documents;
    }

    /** 
     * Request to select users by login
     *
     * @param string|null $login
     * @return array
     */    
    public function noSqlGetUsersDatas(
        ?string $login = null
    ):array
    {

        $documents = [];

        $result = $this->db(1)
            ->selectCollection('useraccount')
            ->find(['loginusers' => $login]);

        foreach ($result as $document) {
            $documents[] = $document;
        }

        return $documents;
    }

   /** 
     * Request to select users by login
     *
     * @param string|null $login
     * @return array
     */    
    public function noSqlRedisGetUsersDatas(
        ?string $login = null
    ):array
    {

        $result = [];

        $result = $this->key('useraccount')->index($login)->redisGet();

        return $result;
    }    
    
    /** 
     * Request to select users actions list by login
     *
     * @param string|null $login
     * @return array
     */
    public function noSqlGetUsersRecentsActions(
        ?string $login = null
    ):array
    {

        $documents = [];

        $result = $this->db(1)
            ->selectCollection('recentactions')
            ->find(['usersactions' => $login ]);

        foreach ($result as $document) {
            $documents[] = $document;
        }

        return  $documents;
    }     
}