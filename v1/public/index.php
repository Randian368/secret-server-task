<?php

require __DIR__.'/../vendor/autoload.php';

$config_files = glob(__DIR__.'/../config/*.php');
foreach($config_files as $config_file) {
  include_once $config_file;
}

if(strpos($_SERVER['SERVER_PROTOCOL'], 'HTTP') !== false) {
  if(ApiRequest::isSupportedMethod()) {
    if(isset($_GET['route']) && !empty($_GET['route'])) {
      $route_builder = new \Builder\RouteBuilder();
      $route = $route_builder->build($_GET['route']);

      if($route !== null) {
        $response = $route->visit();

        // output the response
      } else {

      }
    }
  } else {
    // error handling
  }
}
