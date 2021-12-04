<?php

require __DIR__.'/../vendor/autoload.php';

$config_files = glob(__DIR__.'/../config/*.php');
foreach($config_files as $config_file) {
  include_once $config_file;
}
