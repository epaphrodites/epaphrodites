<?php

namespace Epaphrodites\controllers\render\Twig;

use Epaphrodites\epaphrodites\Contracts\twigRender as ContractsTwigRender;

class TwigRender extends TwigConfig implements ContractsTwigRender{

    /**
     * Twig render
     *
     * @param string|null $view
     * @param array|[] $array
     * @return void
     */ 
    public function render( 
      string|null $view = null, 
      array $array = [] 
    ):void
    {

      try {

        echo $this->getTwigEnvironmentInstance()->render($view . _FRONT_ , $array );
    } catch (\Throwable $e) {
        if (!_PRODUCTION_) {
            echo '<pre>' . $e . '</pre>';
        } else {
            // En production : log ou message générique
            error_log($e);
            echo 'Une erreur est survenue. Veuillez réessayer plus tard.';
        }
    }
    

    }    

}