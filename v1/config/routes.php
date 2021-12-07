<?php

/*
 *  - class / file names need to be denoted in snake_case
 *  - the beginning(^) point of patters is v1, endpoint($) is the end of the url string
 * - patterns are interpreted in a case insensitive manner
 */

$config_routes = [
  '^secret$'              => 'secret_server/post',
  '^secret\/([^\s\/]+)'   => 'secret_server/get/$1'
];

define('CONFIG_ROUTES', $config_routes);
unset($config_routes);
