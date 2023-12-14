<?php declare(strict_types=1);

namespace Epaphrodite\controllers\render\Http;

class ConfigHttp extends HttpClient
{

    /**
     * Get the provider URL
     *
     * @param array|null $url
     * @return string
     */
    protected function provider(?array $url = null): string
    {

        return static::class('session')->id() !== null && count($url) > 1
            ? $url[0] . '/' . $url[1] . _MAIN_EXTENSION_
            : $url[1] . _MAIN_EXTENSION_;
    }

    /**
     * Parse the HTTP method
     *
     * @return string
     */
    protected function ParseMethod(): string
    {
        return $this->HttpRequest() !== "/" && $this->HttpRequest()[-1] === "/"
            ? substr($this->HttpRequest(), 1)
            : _DASHBOARD_;
    }
}