<?php

try {
  $router->group(['prefix' => 'admin', 'controller' => 'admin', 'middlewares' => ['auth']], function () {
    $this->add('/', 'GET', 'AdminController:index');
    $this->add('/user/(:alpha)', 'GET', 'UserController:index', ["userName"]);
  });
  $router->add('/', 'GET', 'HomeController:index');
  $router->add('/product/(:alpha)', 'GET', 'ProductController:show')->options(['prefix' => 'site', 'controller' => 'site', 'middlewares' => []]);
  $router->init();
} catch (\Exception $e) {
  dd($e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine());
}