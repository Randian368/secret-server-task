<?php
ini_set('display_errors', 'off');

try {
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
  } else {
    $route = new Route();
  }

  $response = $request->getResponse($route);

} catch (\Exception | \ErrorException | \Error $e) {
  $request = new \Request();
  $response = $request->getExceptionResponse();
}

$response_formatter_factory = new \Factory\ResponseFormatterFactory();
$response_formatter = $response_formatter_factory->create($request->getAcceptMimeType());

$response_formatter->format($response);

$response->output();
