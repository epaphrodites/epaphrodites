<?php

namespace Epaphrodites\epaphrodites\heredia\herediaSettingTraits;

trait mainUsersOptions{

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

}