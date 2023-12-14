<?php

namespace Epaphrodites\epaphrodites\heredia;

use Epaphrodites\epaphrodites\constant\epaphroditeClass;
use Epaphrodites\epaphrodites\Contracts\settingHeredia as ContractsSettingHeredia;

class SettingHeredia extends epaphroditeClass implements ContractsSettingHeredia
{


    /**
     * @return void
     */

    private object $msg;
    private object $datas;
    private object $paths;
    private object $count;
    private object $layouts;
    private object $session;

    /**
     * Initialize object properties when an instance is created
     * @return void
     */
    public function __construct()
    {
        $this->initializeObjects();
    }

    /**
     * Set main_init layouts params
     * 
     * @return array
     */
    public function MainUserInitLayouts(): array
    {
        return
            [
                /*
            |--------------------------------------------------------------------------
            | Set path to front in default
            |--------------------------------------------------------------------------
            */
                'path' => $this->paths,

                /*
            |--------------------------------------------------------------------------
            | Set datas to front in default
            |--------------------------------------------------------------------------
            */
                'data' => $this->datas,

                /*
            |--------------------------------------------------------------------------
            | Set messages text to front in default
            |--------------------------------------------------------------------------
            */
                'messages' => $this->msg,

                /*
            |--------------------------------------------------------------------------
            | Set form to front in default
            |--------------------------------------------------------------------------
            */
                'forms' => $this->layouts->forms(),

                /*
            |--------------------------------------------------------------------------
            | Set message layout to front in default
            |--------------------------------------------------------------------------
            */
                'message' => $this->layouts->msg(),

                /*
            |--------------------------------------------------------------------------
            | Set login (Choose Name and surname default) to front in default
            |--------------------------------------------------------------------------
            */
                'login' => $this->session->nomprenoms(),

                /*
            |--------------------------------------------------------------------------
            | Set pagination layout to front in default
            |--------------------------------------------------------------------------
            */
                'pagination' => $this->layouts->pagination(),

                /*
            |--------------------------------------------------------------------------
            | Set breadcrumb layout to front in default
            |--------------------------------------------------------------------------
            */
                'breadcrumb' => $this->layouts->breadcrumbs(),
                
                /*
            |--------------------------------------------------------------------------
            | Set ajax layout to front in default
            |--------------------------------------------------------------------------
            */
            'ajax' => $this->layouts->ajax(),


            ];
    }

    /**
     * Set admin_init layouts params
     * 
     * @return array
     */
    public function AdminInitMainLayouts(): array
    {

        return
            [

                /*
            |--------------------------------------------------------------------------
            | Set datas to front in default
            |--------------------------------------------------------------------------
            */
                'data' => $this->datas,

                /*
            |--------------------------------------------------------------------------
            | Set path to front in default
            |--------------------------------------------------------------------------
            */
                'path' => $this->paths,

                /*
            |--------------------------------------------------------------------------
            | Set messages text to front in default
            |--------------------------------------------------------------------------
            */
                'messages' => $this->msg,

                /*
            |--------------------------------------------------------------------------
            | Set message layout to front in default
            |--------------------------------------------------------------------------
            */
                'message' => $this->layouts->msg(),

                /*
            |--------------------------------------------------------------------------
            | Set form to front in default
            |--------------------------------------------------------------------------
            */
                'forms' => $this->layouts->forms(),

                /*
            |--------------------------------------------------------------------------
            | Set login (Choose Name and surname) to front in default
            |--------------------------------------------------------------------------
            */
                'login' => $this->session->nomprenoms(),

                /*
            |--------------------------------------------------------------------------
            | Set pagination layout to front in default
            |--------------------------------------------------------------------------
            */
                'pagination' => $this->layouts->pagination(),

                /*
            |--------------------------------------------------------------------------
            | Set ajax layout to front in default
            |--------------------------------------------------------------------------
            */
            'ajax' => $this->layouts->ajax(),

                /*
            |--------------------------------------------------------------------------
            | Set breadcrumb layout to front in default
            |--------------------------------------------------------------------------
            */
                'breadcrumb' => $this->layouts->breadcrumbs(),

                /*
            |--------------------------------------------------------------------------
            | Count users messages from super admin
            |--------------------------------------------------------------------------
            */
                'notification' => $this->count->chat_messages(),

            ];
    }

    /**
     * Set admin layouts params
     * 
     * @return array
     */
    public function AdminLayout(): array
    {

        return [
                /*
            |--------------------------------------------------------------------------
            | Set admin layout to front in default
            |--------------------------------------------------------------------------
            */
            'layouts' => $this->layouts->admin($this->session->type()),
        ];
    }

    /**
     * Set Main layouts params
     * 
     * @return array
     */
    public function MainLayout(): array
    {

        return [
                /*
            |--------------------------------------------------------------------------
            | Set main layout to front in default
            |--------------------------------------------------------------------------
            */
            'layouts' => $this->layouts->main(),
        ];
    }

    /**
     * Set main params
     * 
     * @return array
     */
    public function coookies(): array
    {
        return
            [

                /*
            |--------------------------------------------------------------------------
            | Session lifetime
            |--------------------------------------------------------------------------
            |
            | Supported: "lax", "strict", "none", null
            |
            */
                'expires' => time() + 86400,

                /*
            |--------------------------------------------------------------------------
            | Session Cookie Path
            |--------------------------------------------------------------------------
            |
            | Supported: "lax", "strict", "none", null
            |
            */
                'path' => '/',


                'domain' => '',

                /*
            |--------------------------------------------------------------------------
            | Secure Cookies
            |--------------------------------------------------------------------------
            |
            | Supported: "false", "true"
            |
            */
                'secure' => true,

                /*
            |--------------------------------------------------------------------------
            | httponly Cookies
            |--------------------------------------------------------------------------
            |
            | Supported: "false", "true"
            |
            */
                'httponly' => true,

                /*
            |--------------------------------------------------------------------------
            | Same-Site Cookies
            |--------------------------------------------------------------------------
            |
            | Supported: "lax", "strict", "none", null
            |
            */
                'samesite' => 'strict'
            ];
    }

    /**
     * Set main params
     * 
     * @return array
     */
    public function session_params(): array
    {
        return
            [

                /*
            |--------------------------------------------------------------------------
            | Session lifetime
            |--------------------------------------------------------------------------
            |
            | Supported: "lax", "strict", "none", null
            |
            */
                'lifetime' => 86400,

                /*
            |--------------------------------------------------------------------------
            | Session Cookie Path
            |--------------------------------------------------------------------------
            |
            | Supported: "lax", "strict", "none", null
            |
            */
                'path' => '/',

                /*
            |--------------------------------------------------------------------------
            | httponly Cookies
            |--------------------------------------------------------------------------
            |
            | Supported: "false", "true"
            |
            */
                'httponly' => true,

                /*
            |--------------------------------------------------------------------------
            | Same-Site Cookies
            |--------------------------------------------------------------------------
            |
            | Supported: "lax", "strict", "none", null
            |
            */
                'samesite' => 'Strict',
            ];
    }

    /**
     * Set others options
     * @return array
     */
    public function others_options(): array
    {
        return
            [

                /*
            |--------------------------------------------------------------------------
            | Secure session
            |--------------------------------------------------------------------------
            |
            | Supported: "true", "false"
            |
            */
                'secure' => true
            ];
    }

    /**
     * Initialize each property using values retrieved from static configurations
     * @return void
     */
    private function initializeObjects(): void
    {
        $this->msg = $this->getObject(static::$initNamespace, 'msg');
        $this->datas = $this->getObject(static::$initNamespace, 'datas');
        $this->paths = $this->getObject(static::$initNamespace, 'paths');
        $this->count = $this->getObject(static::$initQueryConfig, 'count');
        $this->layouts = $this->getObject(static::$initNamespace, 'layout');
        $this->session = $this->getObject(static::$initNamespace, 'session');
    }
}
