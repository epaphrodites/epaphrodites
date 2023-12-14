<?php

namespace Epaphrodite\epaphrodite\yedidiah;

use Epaphrodite\epaphrodite\constant\epaphroditeClass;

class UpdateRights extends epaphroditeClass
{

    /**
     * Request to update users rights
     * 
     * @param int|null $IdTypeUsers
     * @param int|null $etat
     * @return bool
     */
    public function UpdateUsersRights( ?string $IdTypeUsers = null, ?int $etat = null ): bool
    {

        $JsonDatas = json_decode(file_get_contents(static::JsonDatas()), true);

        foreach ($JsonDatas as $key => $value) {

            if ($value['IndexRight'] == $IdTypeUsers) {
                $JsonDatas[$key]['Autorisations'] = $etat;
            }
        }

        file_put_contents(static::JsonDatas(), json_encode($JsonDatas));

        return true;
    }

}