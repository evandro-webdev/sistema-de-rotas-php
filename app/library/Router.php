<?php

namespace app\library;

use Closure;

class Router
{
  private array $routes = [];
  private array $routeOptions = [];
  private Route $route;

  public function add(
    string $uri,
    string $request,
    string $controller,
    array $wildcardAliases = []
  ) {
    $this->route = new Route($request, $controller, $wildcardAliases);
    $this->route->addRouteUri(new Uri($uri));
    $this->route->addRouteGroupOptions(new RouteOptions($this->routeOptions));
    $this->route->addRouteWildcard(new RouteWildcard());
    $this->routes[] = $this->route;

    return $this;
  }

  public function group($routeOptions, Closure $callback)
  {
    $this->routeOptions = $routeOptions;
    $callback->call($this);
    $this->routeOptions = [];
  }

  public function middleware(array $middlewares)
  {
    $options = [];
    $options = !empty($this->routeOptions) ?
      array_merge($this->routeOptions, ['middlewares' => $middlewares]) :
      ['middlewares' => $middlewares];

    $this->route->addRouteGroupOptions(new RouteOptions($options));
  }

  public function options(array $options)
  {
    if (!empty($this->routeOptions)) {
      $options = array_merge($this->routeOptions, $options);
    }

    $this->route->addRouteGroupOptions(new RouteOptions($options));
  }

  public function init()
  {
    foreach ($this->routes as $route) {
      if ($route->match()) {
        return (new Controller)->call($route);
      }
    }

    return (new Controller)->call(new Route('GET', 'NotFoundController:index', []));
  }
}