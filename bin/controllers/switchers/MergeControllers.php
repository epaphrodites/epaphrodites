<?php

declare(strict_types=1);

namespace Epaphrodite\controllers\switchers;

use Epaphrodite\epaphrodite\constant\epaphroditeClass;
use Epaphrodite\epaphrodite\heredia\SwitchersHeredia;

class MergeControllers extends epaphroditeClass implements contractController
{

    /**
     * @param object $class
     * @param mixed $pages
     * @param bool $switch
     * @return mixed
     */
    public function SwitchControllers(object $class, string $pages, ?bool $switch = false): mixed
    {

        $targetFunction = $this->transformToFunction($pages);

        $switch === false ?: $this->checkAutorisation($pages, $switch);

        return static::directory($pages, $switch) == false ? static::class('errors')->error_404() : $class->$targetFunction($pages);
    }

    /**
     * @param object $class
     * @param mixed $pages
     * @return mixed
     */
    public function SwitchApiControllers(object $class, string $pages): mixed
    {

        $targetFunction = $this->transformToFunction($pages);
        return $class->$targetFunction();
    }

    /**
     * @param string|null $html
     * @param bool|false $switch
     * @return bool
     */
    private static function directory(?string $html = null, ?bool $switch = false): bool
    {

        return $switch === false ? file_exists(_DIR_VIEWS_ . _DIR_MAIN_TEMP_ . $html . _FRONT_) : file_exists(_DIR_VIEWS_ . _DIR_ADMIN_TEMP_ . $html . _FRONT_);
    }

    /**
     * @param string|null $target
     * @param null|bool $autorize
     * @return bool|null
     */
    private function checkAutorisation($target, $autorize): bool|null
    {

        return (new SwitchersHeredia)->swicthPagesAutorisation($target, $autorize);
    }

    /**
     *  @param string $initPage
     * @return string
     */
    private function transformToFunction($initPage): string
    {

        $extension = _MAIN_EXTENSION_;

        $initPage = preg_replace("/$extension$/", '', $initPage);

        $parts = explode('_', $initPage);

        $camelCaseParts = array_map(function ($part) {
            return ucfirst($part);
        }, $parts);

        $camelCaseString = implode('', $camelCaseParts);

        $contract = explode('/', $camelCaseString);

        $parts = count($contract) > 1 ? $contract[1] : $contract[0];

        return $parts;
    }
}
