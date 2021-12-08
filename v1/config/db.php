<?php
if(defined('ENV') && ENV == 'REMOTE') {
  /* redacted live server information */
} else {
  define('CONFIG_DB_HOSTNAME', 'localhost');
  define('CONFIG_DB_USERNAME', 'root');
  define('CONFIG_DB_DATABASE', 'secret_server');
  define('CONFIG_DB_PASSWORD', '');
}
