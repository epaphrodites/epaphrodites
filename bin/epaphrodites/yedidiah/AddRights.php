<?php

namespace Epaphrodites\epaphrodites\yedidiah;

use Epaphrodites\epaphrodites\constant\epaphroditeClass;

class AddRights extends epaphroditeClass{

    /**
     * Add users rights
     * index ( module , type_user , idpage , action)
     * @param int|null $idTypeUsers
     * @param string|null $pages
     * @param string|null $actions
     * @return bool
     */
    public function AddUsersRights(?int $idTypeUsers = null, ?string $ModulePages = null,  ?string  $actions = null):bool
    {

        $JsonDatas = file_get_contents(static::JsonDatas());

        $pages = explode( '@' ,$ModulePages);

        if (!empty($idTypeUsers) && !empty($pages) && !empty($JsonDatas) && $this->IfRightExist($idTypeUsers, $pages[1] , $JsonDatas) === false) {

            $SaveRights = json_decode($JsonDatas , true);

            $SaveRights[] = 
                [
                    'IduserRights' => count($SaveRights) + 1,
                    'IdtypeUserRights' => $idTypeUsers,
                    'Autorisations' => $actions,
                    'Modules' => $pages[0],
                    'IndexModule' => $pages[0] . ',' . $idTypeUsers,
                    'IndexRight' => md5($idTypeUsers . ',' . $pages[1]),
                ];

            file_put_contents( static::JsonDatas(), json_encode($SaveRights));

            return true;
        } else {
            return false;
        }
    }

    /**
     * Request to select user right if exist
     * @return bool
     */
    public function IfRightExist($idTypeUsers, $pages , $JsonDatas):bool
    {

        $result = false;
        
        $GetJsonArray = json_decode( $JsonDatas , true);
        $index = md5($idTypeUsers . ',' . $pages);
        foreach ($GetJsonArray as $key => $value) {
            if ($value['IndexRight'] == $index) {
                $result = true;
            }
        }

        return $result;
    }    
}