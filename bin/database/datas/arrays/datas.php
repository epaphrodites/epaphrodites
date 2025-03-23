<?php

namespace Epaphrodites\database\datas\arrays;

class datas
{

    /**
     * users Group list
     * 
     * @param int|string|null $key
     * @param string $need
     * @return array|int|string|null
     */
    public function usersGroup(
        int|string|null $key = null,
        string $need = '_id'
    ): array|int|string|null
    {

        $list = [
            1 => [ '_id' => 1, 'label' => 'SUPER ADMINISTRATOR' ],
            2 => [ '_id' => 2, 'label' => 'ADMINISTRATOR' ],
            3 => [ '_id' => 3, 'label' => 'USERS' ],
        ];

        return $this->returnData($list, $key, $need );
    }

    /**
     * Get the list of dashboard colors or a specific color property by key.
     *
     * @param string|int|null $key The index of the color to retrieve (null to return the full list).
     * @param string $property The property to return ('_id' or 'label', defaults to '_id').
     * @return array{_id: string, label: string[]|string|null}
     */
    public function colorsList(
        string|int|null $key = null,
        string $need = '_id'
    ): array|string|null {

        $list = [
            ['_id' => 'main', 'label' => 'Main Colors'],
            ['_id' => 'noella', 'label' => 'Noella Colors'],
            ['_id' => 'shlomo', 'label' => 'Shlomo Colors'],
            ['_id' => 'yedidia', 'label' => 'Yedidiah Colors'],
            ['_id' => 'eklou', 'label' => 'Eklou Colors'],
        ];

        return $this->returnData($list, $key, $need );
    }
      
    /**
     * Authorization actions
     *
     * @return array
     */
    public function autorisation(): array {

        return [
            1 => 'DENY',
            2 => 'ALLOW',
        ];
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
    
    /**
     * Summary of returnData
     * 
     * @param array $list
     * @param int|string|null $key
     * @param string $need
     * @return array|int|string|null
     */
    private function returnData( 
        array $list = [],
        int|string|null $key = null,
        string $need = '_id'
    ): array|int|string|null{

        if ($key === null) {
            return array_values($list);
        }

        if (!isset($list[$key])) {
            return null;
        }

        return $list[$key][$need] ?? null;
    }
}