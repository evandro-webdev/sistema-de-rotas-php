<?php

namespace app\enums;

use app\middlewares\Auth;

enum RouteMiddlewares: string
{
  case auth = Auth::class;
}