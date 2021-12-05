<?php
require __DIR__.'/./startup.php';


$request = new \Request();

/*
* apache mod_rewrite modifies requests to the api version folders to be redirected to this file
* and to include the rest of the path as the route parameter
*/

if(isset($_GET['route']) && !empty($_GET['route'])) {
  $route_path = $_GET['route'];

  $route_builder = new \Builder\RouteBuilder();
  $route = $route_builder->build($route_path);

  $response = $request->getResponse($route);
}
