<?php

namespace Epaphrodite\epaphrodite\Contracts;

interface twigRender{

    /**
     * @param string|null $view
     * @param array|[] $array
     * @return mixed
    */     
    public function render( string $view = null , array $array = [] );
}