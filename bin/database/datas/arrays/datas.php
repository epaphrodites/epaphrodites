<?php

namespace Epaphrodites\database\datas\arrays;

class datas
{

    /**
     * List of users group
     * 
     * @param int $key
     * @return array|null
     */
    public function userGroup(
        int|null $key = null
    ): array|string|int|null
    {
        $list = [
            1 => 'SUPER ADMINISTRATOR',
            2 => 'ADMINISTRATOR',
            3 => 'USERS',
        ];
    
        return $key === null ? $list : ($list[$key] ?? 0);
    }

    /**
     * Get the list of dashboard colors or a specific color by key.
     * 
     * @param int|null $key The index of the color to retrieve (null to return the full list).
     * @return array|int The list of colors or a specific color by key, or 0 if the key is invalid.
     */
    public function colorsList(
        int|null $key = null
    ): array|string|int|null{
        $list = [
            [ '_id' => 'main', 'label' => 'MAIN COLORS' ],
            [ '_id' => 'noella', 'label' => 'NOELLA COLORS' ],
            [ '_id' => 'shlomo', 'label' => 'SHLOMO COLORS' ],
            [ '_id' => 'yedidia', 'label' => 'YEDIDIAH COLORS' ],
            [ '_id' => 'eklou', 'label' => 'EKLOU COLORS' ],
        ];
        
        return $key === null ? $list : ($list[$key] ?? 0);
    }
      
    
    /**
     * Authorization actions
     *
     * @param int|null $key Authorization key
     * @return array|string|int
     */
    public function autorisation(
        int|null $key = null
    ): array|string|int|null {

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