<?php

namespace Epaphrodites\epaphrodites\heredia\herediaSettingTraits;

trait othersOptions{

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

}