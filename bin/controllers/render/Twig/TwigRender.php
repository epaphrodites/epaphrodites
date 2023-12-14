<?php

namespace Epaphrodite\controllers\render\Twig;

use Epaphrodite\epaphrodite\Contracts\twigRender as ContractsTwigRender;

class TwigRender extends TwigConfig implements ContractsTwigRender{

    /**
     * Twig render
     *
     * @param string|null $view
     * @param array|[] $array
     * @return void
     */ 
    public function render( string $view = null , array $array = [] ):void
    {
        
      echo $this->getTwigEnvironmentInstance()->render($view . _FRONT_ , $array );
    }    

}