<?php

namespace app\library;

use app\enums\RouteWildcard as EnumsRouteWildcard;

class RouteWildcard
{
  private string $wildcardReplaced;
  private array $params = [];

  public function paramsToArray(string $uri, string $wildcard, array $aliases)
  {
    $explodeUri = explode("/", ltrim($uri, "/"));
    $explodeWildcard = explode("/", ltrim($wildcard, "/"));

    $uriDiff = array_diff($explodeUri, $explodeWildcard);

    $indexAliases = 0;
    foreach ($uriDiff as $index => $param) {
      if (!$aliases) {
        $this->params[array_values($explodeUri)[$index - 1]] = is_numeric($param) ? (int) $param : $param;
      } else {
        $this->params[$aliases[$indexAliases]] = is_numeric($param) ? (int) $param : $param;
        $indexAliases++;
      }
    }
  }

  public function replaceWithPattern(string $uriToReplace)
  {
    $this->wildcardReplaced = $uriToReplace;
    if (str_contains($this->wildcardReplaced, '(:numeric)')) {
      $this->wildcardReplaced = str_replace('(:numeric)', EnumsRouteWildcard::numeric->value, $this->wildcardReplaced);
    }

    if (str_contains($this->wildcardReplaced, '(:alpha)')) {
      $this->wildcardReplaced = str_replace('(:alpha)', EnumsRouteWildcard::alpha->value, $this->wildcardReplaced);
    }

    if (str_contains($this->wildcardReplaced, '(:any)')) {
      $this->wildcardReplaced = str_replace('(:any)', EnumsRouteWildcard::any->value, $this->wildcardReplaced);
    }
  }

  public function uriEqualToPattern($currentUri, $wildcardReplaced)
  {
    $wildcardReplaced = str_replace("/", "\/", ltrim($wildcardReplaced, "/"));
    return preg_match("/^$wildcardReplaced$/", ltrim($currentUri, "/"));
  }

  public function getParams()
  {
    return $this->params ? [...$this->params] : [];
  }

  public function getWildcardReplaced(): string
  {
    return $this->wildcardReplaced;
  }
}