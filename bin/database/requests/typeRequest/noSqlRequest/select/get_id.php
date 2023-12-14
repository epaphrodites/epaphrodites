<?php

namespace Epaphrodite\database\requests\typeRequest\noSqlRequest\select;

use Epaphrodite\database\query\Builders;

class get_id extends Builders
{

    /**
     * Afficher la liste des utilisateurs
     *
     * @param integer $page
     * @param integer $Nbreligne
     * @param integer $UsersGroup
     * @return array
     */
    public function noSqlGetUsersByGroup(int $page, int $Nbreligne, int $UsersGroup):array
    {

        $documents = [];

        $result = $this->db(1)
            ->selectCollection('useraccount')
            ->find(['typeusers' => $UsersGroup], [
                'limit' => $Nbreligne, 'skip' => ($page-1),
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
    public function noSqlGetUsersDatas(?string $login = null)
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
     * Request to select users actions list by login
     *
     * @param string|null $login
     * @return array
     */
    public function noSqlGetUsersRecentsActions(?string $login = null)
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
