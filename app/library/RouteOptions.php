<?php

namespace app\library;

class RouteOptions
{
  public function __construct(private readonly array $routeOptions) {}

  public function optionExist($index)
  {
    return !(empty($this->routeOptions)) && isset($this->routeOptions[$index]);
  }

  public function execute($index)
  {
    return $this->routeOptions[$index];
  }
}
