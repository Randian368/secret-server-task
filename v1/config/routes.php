<?php
$config_routes = [
  'secret'              => 'SecretServer/post',
  'secret\/([^\s\/]+)'  => 'SecretServer/get/$1'
];

define('CONFIG_ROUTES', $config_routes);
unset($config_routes);
