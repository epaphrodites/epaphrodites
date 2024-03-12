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
    public function defaultSqlListeOfAllUsers(
        int $page, 
        int $numLines
    ):array{

        $result = $this->table('useraccount')
            ->orderBy('usersgroup', 'ASC')
            ->limit((($page - 1) * $numLines), $numLines)
            ->SQuery();

        return $result;
    }

    /**
     * Request to get users list
     *
     * @param integer $page
     * @param integer $numLines
     * @return array
     */
    public function sqlServerListeOfAllUsers(
        int $page,
        int $numLines
    ):array{

        $result = $this->table('useraccount')
            ->orderBy('usersgroup', 'ASC')
            ->offset((($page - 1) * $numLines), $numLines)
            ->SQuery();

        return $result;
    }    

    /**
     * Request to get users list
     *
     * @param integer $page
     * @param integer $numLines
     * @return array
     */
    public function sqlListOfRecentActions(
        int $page, 
        int $numLines
    ):array{

        return match (_FIRST_DRIVER_) {

        'sqlserver' => $this->sqlServerListOfRecentActions( $page, $numLines),

        default => $this->defaultSqlListOfRecentActions( $page, $numLines)
        };
    } 

    /**
     * Request to get list of users recents actions
     *
     * @param integer $page
     * @param integer $numLines
     * @return array
     */
    public function defaultSqlListOfRecentActions(
        int $page,
        int $numLines
    ):array{

        $result = $this->table('recentactions')
            ->orderBy('dateactions', 'ASC')
            ->limit((($page - 1) * $numLines), $numLines)
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
    public function sqlServerListOfRecentActions( 
        int $page,
        int $numLines
    ):array{

        $result = $this->table('recentactions')
            ->orderBy('dateactions', 'ASC')
            ->offset((($page - 1) * $numLines), $numLines)
            ->SQuery();

        return $result;
    }     
}