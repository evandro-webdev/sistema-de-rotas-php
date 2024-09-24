<?php

namespace app\middlewares;

use app\interfaces\MiddlewareInterface;
use app\library\Auth as LibraryAuth;

class Auth implements MiddlewareInterface
{
  public function execute()
  {
    if (!LibraryAuth::isAuth()) {
      header('location: /');
    }
  }
}