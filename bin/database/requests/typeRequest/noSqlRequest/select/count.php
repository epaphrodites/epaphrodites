<?php

namespace Epaphrodites\database\requests\typeRequest\noSqlRequest\select;

use Epaphrodites\database\query\Builders;

class count extends Builders
{

   /**
    * Request to create 
    * @return int
    */ 
    public function noSqlChatMessages():int
    {

        $login = static::initNamespace()['session']->login();

        $result = $this->db(1)
            ->selectCollection('chatsmessages')
            ->countDocuments(['destinataire' => $login, 'etatmessages' => 1]);

        return $result;
    }

   /**
    * Request to create 
    * @return int
    */ 
    public function noSqlRedisChatMessages():int
    {

        $login = static::initNamespace()['session']->login();

        $result = $this->key('chatsmessages')->search(['destinataire' , 'etatmessages'])->param([ $login , 1])->count()->redisGet();

        return $result;
    }

    /**
     * Get total number of users db
     * @return int
     */
    public function noSqlCountAllUsers():int
    {
        $result = $this->db(1)
            ->selectCollection('useraccount')
            ->countDocuments([]);

        return $result;
    }

    /**
     * Get total number of users db
     * @return int
     */
    public function noSqlRedisCountAllUsers():int
    {

        $result = $this->key('useraccount')->all()->count()->redisGet();

        return $result;
    }    

    /** 
     * Get total number of users db per group
     * @return int
     */
    public function noSqlCountUsersByGroup(
        int $Group
    ):int
    {

        $result = $this->db(1)
            ->selectCollection('useraccount')
            ->countDocuments(['usersgroup' => $Group]);

        return $result;
    }

    /** 
     * Get total number of users db per group
     * @return int
     */
    public function noSqlRedisCountUsersByGroup(
        int $Group
    ):int
    {

        $result = $this->key('useraccount')->search(['usersgroup'])->param([$Group])->all()->count()->redisGet();

        return $result;
    }    

    /** 
     * Get total number of users recent actions
     * @return int
     */
    public function noSqlCountUsersRecentActions():int
    {
        $result = $this->db(1)
            ->selectCollection('recentactions')
            ->countDocuments([]);

        return $result;
    }  
    
    /** 
     * Get total number of users recent actions
     * @return int
     */
    public function noSqlRedisCountUsersRecentActions():int
    {

        $result = $this->key('recentactions')->all()->count()->redisGet();

        return $result;
    }      
}