<?php

namespace Epaphrodites\epaphrodites\heredia\herediaSettingTraits;

trait adminUsersOptions{

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
                'login' => $this->session->nameSurname(),

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

}