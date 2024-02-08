<?php

namespace Epaphrodites\epaphrodites\yedidiah;

use Epaphrodites\epaphrodites\constant\epaphroditeClass;

class UpdateRights extends epaphroditeClass
{

    /**
     * Request to update users rights
     * 
     * @param int|null $idTypeUsers
     * @param int|null $etat
     * @return bool
     */
    public function UpdateUsersRights( ?string $idTypeUsers = null, ?int $etat = null ): bool
    {

        $JsonDatas = json_decode(file_get_contents(static::JsonDatas()), true);

        foreach ($JsonDatas as $key => $value) {

            if ($value['IndexRight'] == $idTypeUsers) {
                $JsonDatas[$key]['Autorisations'] = $etat;
            }
        }

        file_put_contents(static::JsonDatas(), json_encode($JsonDatas));

        return true;
    }
}