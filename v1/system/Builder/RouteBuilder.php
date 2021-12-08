<?php
namespace Builder;
use \Helper\StringHelper as StringHelper;

/**
 * @purpose
 * Responsible for finding the class and method that matches the url path and returning a Route instance with this information.
 */
class RouteBuilder {


  /** Builds a route instance.
   * @method build
   * @param string $path             The path part of the request url. E.g.: secret/{hash}
   * @return Route
   */
  public function build(String $path) {
    if($config_route = $this->getConfigRoute($path)) {
      $path = $config_route;
    }

    if($this->isValidPath($path)) {
      $route = $this->buildPrefixedRoute($path, '\Controller\\');
      if(!($route instanceof \Route)) {
        $route = $this->buildPrefixedRoute($path, '\Model\\');
      }

      if(!($route instanceof \Route)) {
        $route = new \Route(); // all properties are null
      }
    } else {
      $route = new \Route(); // all properties are null
    }
    return $route;
  }


  /** Attempts to find a class with the given namespace that matches the path part of the request url.
   * @method buildPrefixedRoute
   * @param string $path                The path part of the request url. E.g.: secret/{hash}
   * @param string $type_prefix         The namespace to search within.
   * @return Route|null
   */
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


  /** Checks if the url path has a specifically configurated responder class and method.
   * @method getConfigRoute
   * @param string $path                The path part of the request url. E.g.: secret/{hash}
   * @return string                     The configured path based on which a responder class and method can be identified, or an empty string if no configuration exists for the supplied url path.
   */
  private function getConfigRoute($path) {
    $result = '';

    if(defined('CONFIG_ROUTES')) {
      foreach(CONFIG_ROUTES as $pattern => $config_route) {
        if(preg_match('/' . $pattern . '/i', $path, $matches)) {
          if(count($matches) > 1) {
            $result = $this->replaceCapturingGroups($config_route, $matches);
          } else {
            $result = $config_route;
          }
        }
      }
    }
    return $result;
  }


  /** Replaces capturing group references with their match.
   * @method replaceCapturingGroups
   * @param string $config_route                A route overwrite defined in config/routes.php that might contain regex backreferences.
   * @param array $matches                      Array containing the matched values to use as replacements.
   * @return string                             $config_route with backreferences replaced by their corresponding value.
   */
  private function replaceCapturingGroups($config_route, $matches) {
    $replaced = $config_route;
    for($i = 1; $i < count($matches); $i++) {
      $replaced = preg_replace('/\$' . $i . '(?:[^\d]|$)/', $matches[$i], $replaced);
    }
    return $replaced;
  }


  private function isValidPath($path) {
    $path = str_replace('\\', '/', $path);
    return preg_match('/(?:[^\s\/]+\/){1,}?[^\s\/]+/', $path);
  }


  /** Equivalent of dirname() but for url paths.
   * @param string $path                The path part of the request url. E.g.: secret/{hash}
   * @return string|null
   */
  private function getLastPathLevel(String $path) {
    $path = str_replace('\\', '/', $path);
    if(preg_match('/\/([^\s\/]+)$/', $path, $matches) && isset($matches[1])) {
      return $matches[1];
    }
  }

}
