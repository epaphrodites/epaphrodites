<?php

namespace Epaphrodites\epaphrodites\auth;

use Epaphrodites\epaphrodites\auth\Contracts\AuthSession;
use Epaphrodites\epaphrodites\define\config\traits\currentVariableNameSpaces;

class session_auth implements AuthSession
{

    use currentVariableNameSpaces;

    protected $msg;
    protected $config;
    protected $result;

    public function __construct()
    {
        $this->initializeObject();
    }

    /**
     * @return void
     */
    private function initializeObject():void{

        $this->msg = $this->getObject( static::$initNamespace , 'msg' );
        $this->config = $this->getObject( static::$initGuardsConfig , 'session' );
    }

    /**
     * 
     * User session login data
     * @return string
     */
    public function login():string|null
    {
        
        return !empty($this->config->GetSessions(_AUTH_LOGIN_)) ? $this->config->GetSessions(_AUTH_LOGIN_) : NULL;
    }

    /**
     * 
     * User session login data
     * @return string
     */
    public function login_other():string|null
    {

        return !empty($this->config->GetSessions(_AUTH_OTHER_LOGIN_)) ? $this->config->GetSessions(_AUTH_OTHER_LOGIN_) : NULL;
    }    

    /**
     * 
     * User session iduser data
     * @return string
     */
    public function id():string|null
    {

        return !empty($this->config->GetSessions(_AUTH_ID_)) ? $this->config->GetSessions(_AUTH_ID_)  : NULL;
    }

    /**
     * 
     * User session type user
     * @var int $type
     * @return int
     */
    public function type():int|null
    {

        return !empty($this->config->GetSessions(_AUTH_TYPE_)) ? $this->config->GetSessions(_AUTH_TYPE_)  : NULL;
    }

    /**
     * 
     * User session nom et prenoms
     * @return string
     */
    public function nomprenoms():string|null
    {

        return !empty($this->config->GetSessions(_AUTH_NAME_)) ? $this->config->GetSessions(_AUTH_NAME_)  : NULL;
    }

    /**
     * 
     * User session email
     * @return string
     */
    public function email():string|null
    {

        return !empty($this->config->GetSessions(_AUTH_EMAIL_)) ? $this->config->GetSessions(_AUTH_EMAIL_)  : NULL;
    }

    /**
     * 
     * User session contact
     * @return string
     */
    public function contact():string|null
    {
        
        return !empty($this->config->GetSessions(_AUTH_CONTACT_)) ? $this->config->GetSessions(_AUTH_CONTACT_)  : NULL;
    }

    /**
     * 
     * User session email data
     * @var mixed $email
     * @return mixed
     */
    public function soumettre($datas)
    {

        $_SESSION['soumettre'] = implode(" ", $datas);

        return $_SESSION['soumettre'];
    }

    /**
     * 
     * User session email data
     * @var mixed $email
     * @return mixed
     */
    public function soumis($datas)
    {
        $_SESSION['soumis'] = implode(" ", $datas);

        return $_SESSION['soumis'];
    }
    
    public function verify_formulaire()
    {
        $this->result = false;

        if (isset($_SESSION['soumis'])) {
            $this->result = $_SESSION['soumettre'] === $_SESSION['soumis'] ? true : false;
        }

        return $this->result;
    }

    /**
     * 
     * User cookies token_csrf data
     * @var mixed $token_csrf
     * @return mixed
     */
    public function token_csrf():mixed
    {
        return !empty($_COOKIE[$this->msg->answers('token_name')]) ? $_COOKIE[$this->msg->answers('token_name')] : NULL;
    }

    /**
     * 
     * Destroy user session
     * @return mixed
     */
    public function deconnexion()
    {

        if ($this->login() !== NULL && $this->id() !== NULL) {

            session_unset();

            session_destroy();
        }
    }
}
