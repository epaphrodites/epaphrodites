<?php

namespace Epaphrodites\database\requests\typeRequest\noSqlRequest\select;

use Epaphrodites\database\query\Builders;

class select extends Builders
{

    /**
     * Request to select users list
     *
     * @param integer $page
     * @param integer $numLines
     * @return array
     */
    public function noSqlListeOfAllUsers( 
        int $page, 
        int $numLines
    ):array
    {

        $documents =[];

        $result = $this->db(1)
            ->selectCollection('useraccount')
            ->find([] , ['limit' => $numLines , 'skip' => ($page -1)] );

        foreach ($result as $document) {
            $documents []= $document;
        }
        
        return $documents;        
    }  
    
   /**
     * Request to select users list
     *
     * @param integer $page
     * @param integer $numLines
     * @return array
     */
    public function noSqlRedisListeOfAllUsers( 
        int $page, 
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
     * @param integer $page
     * @param integer $numLines
     * @return array
     */
    public function noSqlListOfRecentActions( 
        int $page, 
        int $numLines
    ):array
    {

        $documents =[];

        $result = $this->db(1)
            ->selectCollection('recentactions')
            ->find([] , ['limit' => $numLines , 'skip' => ($page -1)] );

        foreach ($result as $document) {
            $documents []= $document;
        }
        
        return $documents;         
    }

    /**
     * Request to get list of users recents actions
     *
     * @param integer $page
     * @param integer $numLines
     * @return array
     */
    public function noSqlRedisListOfRecentActions( 
        int $page, 
        int $numLines
    ):array
    {

        $result = [];

        $result = $this->key('recentactions')->all()->rlimit($page , $numLines)->redisGet();
        
        return $result;         
    }    
}
