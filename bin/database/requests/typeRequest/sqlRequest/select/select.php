<?php

namespace Epaphrodites\database\requests\typeRequest\sqlRequest\select;

use Epaphrodites\database\requests\typeRequest\noSqlRequest\select\select as SelectSelect;

class select extends SelectSelect
{

    /**
     * Request to get users list
     *
     * @param integer $currentPage
     * @param integer $numLines
     * @return array
     */
    public function defaultSqlListeOfAllUsers(
        int $currentPage, 
        int $numLines
    ):array{

        $result = $this->table('usersaccount')
            ->orderBy('usersgroup', 'ASC')
            ->limit((($currentPage - 1) * $numLines), $numLines)
            ->SQuery();

        return $result;
    }

    /**
     * Request to get users list
     *
     * @param integer $currentPage
     * @param integer $numLines
     * @return array
     */
    public function sqlServerListeOfAllUsers(
        int $currentPage,
        int $numLines
    ):array{

        $result = $this->table('usersaccount')
            ->orderBy('usersgroup', 'ASC')
            ->offset((($currentPage - 1) * $numLines), $numLines)
            ->SQuery();

        return $result;
    }    

    /**
     * Request to get users list
     *
     * @param integer $currentPage
     * @param integer $numLines
     * @return array
     */
    public function sqlListOfRecentActions(
        int $currentPage, 
        int $numLines
    ):array{

        return match (_FIRST_DRIVER_) {

        'sqlserver' => $this->sqlServerListOfRecentActions( $currentPage, $numLines),

        default => $this->defaultSqlListOfRecentActions( $currentPage, $numLines)
        };
    } 

    /**
     * Request to get list of users recents actions
     *
     * @param integer $currentPage
     * @param integer $numLines
     * @return array
     */
    public function defaultSqlListOfRecentActions(
        int $currentPage,
        int $numLines
    ):array{

        $result = $this->table('history')
            ->orderBy('date', 'ASC')
            ->limit((($currentPage - 1) * $numLines), $numLines)
            ->SQuery();

        return $result;
    }  
    
    /**
     * Request to get list of users recents actions
     *
     * @param integer $currentPage
     * @param integer $numLines
     * @return array
     */
    public function sqlServerListOfRecentActions( 
        int $currentPage,
        int $numLines
    ):array{

        $result = $this->table('history')
            ->orderBy('date', 'ASC')
            ->offset((($currentPage - 1) * $numLines), $numLines)
            ->SQuery();

        return $result;
    }     
}