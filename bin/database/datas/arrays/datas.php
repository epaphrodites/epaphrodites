<?php

namespace Epaphrodites\database\datas\arrays;

class datas
{

    /**
     * List of users group
     * 
     * @param int $key
     * @return mixed
     */
    public function userGroup(
        int $key = null
    ): mixed
    {
        $list = [
            1 => 'SUPER ADMINISTRATOR',
            2 => 'ADMINISTRATOR',
            3 => 'USERS',
        ];
    
        return $key === null ? $list : ($list[$key] ?? 0);
    }

    /**
     * List of users dasboard colors
     * 
     * @param int $key
     * @return mixed
     */
    public function colorsList(
        int $key = null
    ): mixed
    {
        $list = [
            [ '_id' => 'main' , 'label' => 'MAIN COLORS'],
            [ '_id' => 'noella' , 'label' => 'NOELLA COLORS'],
            [ '_id' => 'shlomo' , 'label' => 'SHLOMO COLORS'],
            [ '_id' => 'yedidia' , 'label' => 'YEDIDIAH COLORS'],
            [ '_id' => 'eklou' , 'label' => 'EKLOU COLORS'],
        ];
    
        return $key === null ? $list : ($list[$key] ?? 0);
    }      
    
    /**
     * Authorization actions
     * 
     * @param int $key
     * @return mixed
     */
    public function autorisation(
        int $key = null
    ): mixed
    {
        $list = [
            1 => 'DENY',
            2 => 'ALLOW',
        ];
    
        return $key === null ? $list : ($list[$key] ?? 0);
    }
    
    /**
     * Validation actions for users
     * 
     * @return array
     */
    public function ActionsUsers():array
    {

       return
            [
                1 => "ENABLE / DISABLE AN ACCOUNT",
                2 => "RESET PASSWORD",
                3 => "UPDATE GROUP",
            ];
    }   
    
    /**
     * Rights actions
     * 
     * @return array
     */
    public function ActionsRights():array
    {
       return
            [
                1 => "GRANT PERMISSION",
                2 => "DENY PERMISSION",
                3 => "DELETE RIGHT",
            ];
    }  
    
    /**
     * Set users colors
     * 
     * @return array
     */
    public function colorsActions():array
    {
       return
            [
                1 => "SET USERS GROUP COLOR"
            ];
    }     
}