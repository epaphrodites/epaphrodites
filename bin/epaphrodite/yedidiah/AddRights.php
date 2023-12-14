<?php

namespace Epaphrodite\epaphrodite\yedidiah;

use Epaphrodite\epaphrodite\constant\epaphroditeClass;

class AddRights extends epaphroditeClass{

 /**
     * Ajouter des droits utilisateurs
     * index ( module , type_user , idpage , action)
     * @param int|null $IdTypeUsers
     * @param string|null $pages
     * @param string|null $actions
     * @return bool
     */
    public function AddUsersRights(?int $IdTypeUsers = null, ?string $ModulePages = null,  ?string  $actions = null)
    {

        $JsonDatas = file_get_contents(static::JsonDatas());

        $pages = explode( '@' ,$ModulePages);

        if (!empty($IdTypeUsers) && !empty($pages) && !empty($JsonDatas) && $this->IfRightExist($IdTypeUsers, $pages[1] , $JsonDatas) === false) {

            $SaveRights = json_decode($JsonDatas, true);

            $SaveRights[] = array(
                'IduserRights' => count($SaveRights) + 1,
                'IdtypeUserRights' => $IdTypeUsers,
                'Autorisations' => $actions,
                'Modules' => $pages[0],
                'IndexModule' => $pages[0] . ',' . $IdTypeUsers,
                'IndexRight' => md5($IdTypeUsers . ',' . $pages[1]),
            );

            file_put_contents( static::JsonDatas(), json_encode($SaveRights));

            return true;
        } else {
            return false;
        }
    }


    /** **********************************************************************************************
     * Request to select user right if exist
     * 
     * @return bool
     */
    public function IfRightExist($IdTypeUsers, $pages , $JsonDatas)
    {

        $result = false;
        
        $GetJsonArray = json_decode( $JsonDatas , true);
        $index = md5($IdTypeUsers . ',' . $pages);
        foreach ($GetJsonArray as $key => $value) {
            if ($value['IndexRight'] == $index) {
                $result = true;
            }
        }

        return $result;
    }    

}