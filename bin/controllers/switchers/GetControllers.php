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
    private function getSwitchMainControllers(
        ?array $provider = [], 
        ?string $paths = null
    ): void
    {
        $controllerMap = (array) $this->controllerMap();
    
        foreach ($controllerMap as $controllerName => $method) {
            if (!is_array($method)) {
                continue;
            }
    
            $switcher = $method[2] ?? false;
            $views = $method[3] ?? null;
    
            if (static::getController($controllerName, $provider, $switcher)) {
                $controllerInstance = $this;
                $methodName = $method[1];
                $arguments = [$method[0], $paths ?? null, $switcher, $views];
    
                call_user_func_array([$controllerInstance, $methodName], $arguments);
                return;
            }
        }
    
        $this->SwitchControllers($this->mainController(), $paths, false , _DIR_MAIN_TEMP_);
    }
    
    /**
     * @param array $provider
     * @param string $paths
     * @return void
     */
    public function SwitchMainControllers(
        ?array $provider = [], 
        ?string $paths = null
    ): void
    {
        $this->getSwitchMainControllers($provider, $paths);
    }
}