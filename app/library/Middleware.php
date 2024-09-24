<?php

namespace app\library;

use app\enums\RouteMiddlewares;
use app\interfaces\MiddlewareInterface;
use Exception;

class Middleware
{
  private string $middlewareClass;

  public function __construct(protected array $middlewares) {}

  public function middlewareExist(string $middleware)
  {
    $middlewareCases = RouteMiddlewares::cases();

    return array_filter(
      $middlewareCases,
      function (RouteMiddlewares $middlewareCase) use ($middleware) {
        if ($middlewareCase->name == $middleware) {
          $this->middlewareClass = $middlewareCase->value;
          return true;
        }
        return false;
      }
    );
  }

  public function execute()
  {
    foreach ($this->middlewares as $middleware) {
      if (!$this->middlewareExist($middleware)) {
        throw new Exception("Middleware $middleware does not exist");
      }

      $middlewareClass = $this->middlewareClass;

      if (!class_exists($middlewareClass)) {
        throw new Exception("Middleware class $middlewareClass does not exist");
      }

      $middlewareClass = new $middlewareClass;

      if (!$middlewareClass instanceof MiddlewareInterface) {
        throw new Exception("Middleware $middlewareClass must implement MiddlewareInterface");
      }

      $middlewareClass->execute();
    }
  }
}