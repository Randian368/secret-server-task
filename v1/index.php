<?php
require __DIR__.'/./startup.php';

$request = new \Request();

$response_formatter_factory = new \Factory\ResponseFormatterFactory();
$response_formatter = $response_formatter_factory->create($request->getAcceptMimeType());


/*
* apache mod_rewrite modifies requests to the api version folders to be redirected to this file
* and to include the rest of the path as the route parameter
*/

if(isset($_GET['route']) && !empty($_GET['route'])) {
  $route_path = $_GET['route'];

  $route_builder = new \Builder\RouteBuilder();
  $route = $route_builder->build($route_path);
} else {
  $route = new Route();
}

$response = $request->getResponse($route);

$response_formatter->format($response);

$response->output();
