<?php

namespace Epaphrodites\database\datas\arrays;

class datas
{

    /**
     * Liste des types utilisateurs
     * @param int $key
     * @return mixed
     */
    public function userGroup(?int $key = null)
    {
        $list =
            [
                1 => 'SUPER ADMINISTRATOR',
                2 => 'ADMINISTRATOR',
                3 => 'USERS',
            ];

        if ($key === null) {
            return $list;
        } elseif (!empty($list[$key])) {
            return $list[$key];
        } else {
            return 0;
        }
    }

    /**
     * Liste des autorisations 
     * @param int $key
     * @return mixed
     */
    public function autorisation(?string $key = null)
    {
        $list =
            [
                1 => 'DENY',
                2 => 'ALLOW',
            ];

        if ($key === null) {
            return $list;
        } elseif (!empty($list[$key])) {
            return $list[$key];
        } else {
            return 0;
        }
    } 

    /**
     * Afficher les qualites du personnel
     *
     * @param int $key
     * @return mixed
     */
    public function ActionsUsers(?int $key = null)
    {

       return
            [
                1 => "ENABLE / DISABLE AN ACCOUNT",
                2 => "RESET PASSWORD",
            ];
    }   
    
    /**
     * Afficher les qualites du personnel
     *
     * @param int $key
     * @return mixed
     */
    public function ActionsCourante(?int $key = null)
    {

       return
            [
                1 => "SET AS DEFAULT",
                2 => "DELETE REQUEST",
            ];
    }  
    
    /**
     * Afficher les qualites du personnel
     *
     * @param int $key
     * @return mixed
     */
    public function ActionsRights(?int $key = null)
    {

       return
            [
                1 => "GRANT PERMISSION",
                2 => "DENY PERMISSION",
                3 => "DELETE RIGHT",
            ];
    }     

}
