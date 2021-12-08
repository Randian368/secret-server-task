<?php
define('ENV', 'LOCAL');

require_once __DIR__.'/./vendor/autoload.php';

load_config_files();

$model = new Model();

function load_config_files() {
  $config_files = glob(__DIR__.'/./config/*.php');
  foreach($config_files as $config_file) {
    include_once $config_file;
  }
}
