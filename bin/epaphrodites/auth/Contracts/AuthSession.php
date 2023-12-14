<?php

namespace Epaphrodites\epaphrodites\auth\Contracts;

interface AuthSession
{

    /**
     * @return string|null
     */
    public function login():string|null;

    /**
     * @return string|null
     */
    public function login_other():string|null;

    /**
     * @return string|null
    */    
    public function id():string|null;

    /**
     * @return int|null
    */    
    public function type():int|null;

    /**
     * @return string|null
     */    
    public function nomprenoms():string|null;

    /**
     * @return string|null
     */    
    public function email():string|null;

    /**
     * @return string|null
     */    
    public function contact():string|null;

    /**
     * @return mixed
     */    
    public function token_csrf():mixed;

}