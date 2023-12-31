<?php

namespace Epaphrodites\controllers\controllers;

use Epaphrodites\controllers\switchers\MainSwitchers;

final class dashboard extends MainSwitchers
{

    private object $count;
    private object $select;

    /**
     * Initialize object properties when an instance is created
     * 
     * @return void
     */
    public final function __construct()
    {
        $this->initializeObjects();
    }    

   /**
     * Initialize each property using values retrieved from static configurations
     * 
     * @return void
     */
    private function initializeObjects(): void
    {
        $this->count = $this->getObject( static::$initQueryConfig , "count");
        $this->select = $this->getObject( static::$initQueryConfig , 'general');
    }      

    /**
     * Dashboard for super admin
     * 
     * @param string $html
     * @return void
     */
    public final function superAdmin(string $html):void
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
     * @return void
     */
    public final function administrator(string $html): void
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
     * @return void
     */
    public final function users(string $html): void
    {

        static::rooter()->target(_DIR_ADMIN_TEMP_ . $html)->content(
            [
                'select' => $this->select,
            ], 
            true 
        )->get();
    }           
}
