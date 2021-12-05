<?php
$predefined_paths = [
  'secret'              => 'SecretServer/post',
  'secret\/([^\s\/]+)'  => 'SecretServer/get/$1'
];

define('PREDEFINED_PATHS', $predefined_paths);
unset($predefined_paths);
