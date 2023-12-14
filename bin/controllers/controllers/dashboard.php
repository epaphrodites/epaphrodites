<?php

namespace Epaphrodites\controllers\controllers;

use Epaphrodites\controllers\switchers\MainSwitchers;

final class dashboard extends MainSwitchers
{

    private object $count;
    private object $select;

   /**
     * Initialize each property using values retrieved from static configurations
     * @return void
     */
    private function initializeObjects():void
    {
        $this->count = $this->getObject( static::$initQueryConfig , "count");
        $this->select = $this->getObject( static::$initQueryConfig , 'general');
    }    

    /**
     * @return void
     */
    public function __construct()
    {
        $this->initializeObjects();
    }    

    /**
     * Dashboard for super admin
     * 
     * @param string $html
     * @return mixed
     */
    public function superAdmin(string $html):void
    {

        static::rooter()->target(_DIR_ADMIN_TEMP_ . $html)->content(
            [
                'select' => $this->select,
                'count' => $this->count,
            ],
            true
        )->get();
    }

    /**
     * Dashboard for admin
     * 
     * @param string $html
     * @return mixed
     */
    public function Administrator(string $html): void
    {

        static::rooter()->target(_DIR_ADMIN_TEMP_ . $html)->content(
            [
                'select' => $this->select,
                'count' => $this->count,
            ],
            true
        )->get();
    } 
    
    /**
     * Dashboard for users
     * 
     * @param string $html
     * @return mixed
     */
    public function Users(string $html): void
    {

        static::rooter()->target(_DIR_ADMIN_TEMP_ . $html)->content(
            [
                'select' => $this->select,
            ], 
            true 
        )->get();
    }        
}
