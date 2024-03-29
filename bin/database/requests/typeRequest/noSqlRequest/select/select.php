<?php

namespace Epaphrodites\database\requests\typeRequest\noSqlRequest\select;

use Epaphrodites\database\query\Builders;

class select extends Builders
{

    /**
     * Request to select users list
     *
     * @param integer $currentPage
     * @param integer $numLines
     * @return array
     */
    public function noSqlListeOfAllUsers( 
        int $currentPage, 
        int $numLines
    ):array
    {

        $documents =[];

        $result = $this->db(1)
            ->selectCollection('useraccount')
            ->find([] , ['limit' => $numLines , 'skip' => ($currentPage -1)] );

        foreach ($result as $document) {
            $documents []= $document;
        }
        
        return $documents;        
    }  
    
   /**
     * Request to select users list
     *
     * @param integer $currentPage
     * @param integer $numLines
     * @return array
     */
    public function noSqlRedisListeOfAllUsers( 
        int $currentPage, 
        int $numLines
    ):array
    {
        $result = [];

        $result = $this->key('useraccount')->all()->lastIndex()->redisGet();

        return $result;        
    } 

    /**
     * Request to get list of users recents actions
     *
     * @param integer $currentPage
     * @param integer $numLines
     * @return array
     */
    public function noSqlListOfRecentActions( 
        int $currentPage, 
        int $numLines
    ):array
    {

        $documents =[];

        $result = $this->db(1)
            ->selectCollection('recentactions')
            ->find([] , ['limit' => $numLines , 'skip' => ($currentPage -1)] );

        foreach ($result as $document) {
            $documents []= $document;
        }
        
        return $documents;         
    }

    /**
     * Request to get list of users recents actions
     *
     * @param integer $currentPage
     * @param integer $numLines
     * @return array
     */
    public function noSqlRedisListOfRecentActions( 
        int $currentPage, 
        int $numLines
    ):array
    {

        $result = [];

        $result = $this->key('recentactions')->all()->rlimit($currentPage , $numLines)->redisGet();
        
        return $result;         
    }    
}
