<?php
namespace Builder;
use \Helper\StringHelper as StringHelper;


class RouteBuilder {
  public function build(String $path) {
    if($predefined_route = $this->getPredefinedPath($path)) {
      $path = $predefined_route;
    }

    if($this->isValidPath($path)) {
      $route = $this->buildPrefixedRoute($path, '\Controller\\');
      if(!$route instanceof \Route) {
        $route = $this->buildPrefixedRoute($path, '\Model\\');
      }

      if(!$route instanceof \Route) {
        $route = new \Route(); // all properties are null
      }
    } else {
      $route = new \Route(); // all properties are null
    }
    return $route;
  }


  private function buildPrefixedRoute(String $path, String $type_prefix) {
    $controller = '';
    $method = '';
    $arg = '';

    $path = str_replace('\\', '/', $path);

    $class_name = $type_prefix . StringHelper::format_as_class_name($path);

    if(!class_exists($class_name)) {
      $class_name = '';

      $path_levels = explode('/', $path);
      $path_levels_without_argument = array_slice($path_levels, 0, -1);

      for($i = 0; $i < count($path_levels); $i++) {
        $method = '';
        $arg = '';

        $class_namespaces = array_map(function($namespace) {
          return StringHelper::format_as_class_name($namespace);
        }, array_slice($path_levels, 0, $i + 1));

        $class_name = $type_prefix . implode('\\', $class_namespaces) . StringHelper::format_as_class_name(implode('/', array_slice($path_levels, $i + 1)));

        if(!class_exists($class_name) && $i <= count($path_levels) - 1) {
          $class_namespaces = array_slice($class_namespaces, 0, -1);
          $class_name = $type_prefix . implode('\\', $class_namespaces) . StringHelper::format_as_class_name(implode('/', array_slice($path_levels_without_argument, $i + 1)));

          if($this->isValidPath(str_replace($type_prefix, '', $class_name))) {
            $arg = $path_levels[count($path_levels) - 1];
          }
        }

        $method = strval($path_levels[$i]);

        if(empty($arg) && isset($path_levels[$i + 1]) && !empty($path_levels[$i + 1])) {
          $arg = $path_levels[$i + 1];
        }

        if(class_exists($class_name)) {
          $route = new \Route();

          $route->setClass(new $class_name);
          $route->setMethod($method);
          $route->setArgument($arg);

          return $route;
        }
      }
    }
  }


  private function getPredefinedPath($path) {
    $result = '';

    if(defined('PREDEFINED_PATHS')) {
      foreach(PREDEFINED_PATHS as $pattern => $predefined_path) {
        if(preg_match('/' . $pattern . '/i', $path, $matches)) {
          if(count($matches) > 1) {
            $result = $this->replaceCapturingGroups($predefined_path, $matches);
          } else {
            $result = $predefined_path;
          }
        }
      }
    }
    return $result;
  }


  private function replaceCapturingGroups($predefined_path, $matches) {
    $replaced = $predefined_path;
    for($i = 1; $i < count($matches); $i++) {
      $replaced = preg_replace('/\$' . $i . '(?:[^\d]|$)/', $matches[$i], $replaced);
    }
    return $replaced;
  }



  private function isValidPath($path) {
    $path = str_replace('\\', '/', $path);
    return preg_match('/(?:[^\s\/]+\/){1,}?[^\s\/]+/', $path);
  }

  private function getLastPathLevel(String $path) {
    $path = str_replace('\\', '/', $path);
    if(preg_match('/\/([^\s\/]+)$/', $path, $matches) && isset($matches[1])) {
      return $matches[1];
    }
  }

}
