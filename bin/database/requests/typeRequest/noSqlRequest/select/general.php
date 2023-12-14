<?php

namespace Epaphrodite\database\requests\typeRequest\noSqlRequest\select;

use Epaphrodite\database\query\Builders;

class general extends Builders
{


    /**
     * Request to select all recent users actions of database
     * @return array
     */
    public function noSqlRecentlyActions():array
    {

        $documents = [];
        $UserConnected = static::initNamespace()['session']->login();

        $result = $this->db(1)
            ->selectCollection('recentactions')
            ->find(['usersactions' => $UserConnected], [
                'limit' => 6, 
                'sort' => [
                    'dateactions' => (date('Y-m-d') == 'DESC') ? 1 : -1
                ]
            ]);

        foreach ($result as $document) {
            $documents[] = $document;
        }

        return $documents;
    }
}
