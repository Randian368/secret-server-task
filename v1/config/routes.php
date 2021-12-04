<?php
$predefined_paths = [
  'secret' => 'SecretServer/createNewSecret',
  'secret\/([^\s\/]+)' => 'SecretServer/getSecretByHash/$1'
];

define('PREDEFINED_PATHS', $predefined_paths);
unset($predefined_paths);
