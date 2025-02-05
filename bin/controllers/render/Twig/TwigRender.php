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
      echo $this->getTwigEnvironmentInstance()->render($view . _FRONT_ , $array );
    }    

}