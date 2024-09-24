<?php

namespace app\library;

use Exception;

class Controller
{
  private function controllerPath($route, $controller)
  {
    return $route->getRouteOptionsInstance() && $route->getRouteOptionsInstance()->optionExist('controller') ?
      "app\\controllers\\{$route->getRouteOptionsInstance()->execute('controller')}\\$controller" :
      "app\\controllers\\$controller";
  }

  public function call(Route $route)
  {
    $controller = $route->controller;

    if (!str_contains($controller, ':')) {
      throw new Exception("Colon needed in the controller $controller");
    }

    [$controller, $action] = explode(':', $controller);
    $controllerPath = $this->controllerPath($route, $controller);

    if (!class_exists($controllerPath)) {
      throw new Exception("Controller $controller does not exist");
    }

    $controller = new $controllerPath;

    if (!method_exists($controller, $action)) {
      throw new Exception("Method $action does not exist in controller $controllerPath");
    }

    if ($route->getRouteOptionsInstance()?->optionExist('middlewares')) {
      (new Middleware($route->getRouteOptionsInstance()->execute('middlewares')))->execute();
    }

    call_user_func_array([$controller, $action], $route->getRouteWildcardInstance()?->getParams() ?? []);
  }
}