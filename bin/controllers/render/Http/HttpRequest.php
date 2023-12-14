<?php

namespace Epaphrodite\controllers\render\Http;

class HttpRequest extends GetHttpMethod
{

    protected function HttpRequest(){

        return $this->SwitchUrlHttp();
    }
}
