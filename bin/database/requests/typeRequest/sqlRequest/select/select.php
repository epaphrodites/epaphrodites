<?php

namespace Epaphrodites\database\requests\typeRequest\sqlRequest\select;

use Epaphrodites\database\requests\typeRequest\noSqlRequest\select\select as SelectSelect;

class select extends SelectSelect
{

    /**
     * Request to get users list
     *
     * @param integer $page
     * @param integer $numLines
     * @return array
     */
    public function sqlListeOfAllUsers( int $page, int $numLines):array
    {

        $result = $this->table('useraccount')
            ->limit((($page - 1) * $numLines), $numLines)
            ->orderBy('usersgroup', 'ASC')
            ->SQuery();

        return $result;
    }

    /**
     * Request to get list of users recents actions
     *
     * @param integer $page
     * @param integer $numLines
     * @return array
     */
    public function sqlListOfRecentActions( int $page, int $numLines):array
    {

        $result = $this->table('recentactions')
            ->limit((($page - 1) * $numLines), $numLines)
            ->orderBy('dateactions', 'ASC')
            ->SQuery();

        return $result;
    }    

}
