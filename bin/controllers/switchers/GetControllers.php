<?php

declare(strict_types=1);

namespace Epaphrodites\controllers\switchers;

use Epaphrodites\controllers\controllerMap\controllerMap;

class GetControllers extends ControllersSwitchers
{
    use controllerMap;

    /**
     * Return true controller
     *
     * @param null|array $provider
     * @param null|string $paths
     * @return void
     */
    private function getSwitchMainControllers(?array $provider = [], ?string $paths = null): void
    {

        $controllerMap = (array) $this->controllerMap();

        foreach ($controllerMap as $controllerName => $method) {

            $switcher = $method[2] ?? false;

            if (static::getController($controllerName, $provider, $switcher)) {
                $controllerInstance = $this;
                $methodName = $method[1];
                $arguments = [$method[0], $paths, $switcher];

                call_user_func_array([$controllerInstance, $methodName], $arguments);
                return;
            }
        }

        $this->SwitchControllers( $this->mainController() , $paths);
    }

    /**
     * @return void
     */
    public function SwitchMainControllers(?array $provider = [], ?string $paths = null): void
    {

        $this->getSwitchMainControllers($provider, $paths);
    }
}
