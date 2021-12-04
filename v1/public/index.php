<?php

require __DIR__.'/../vendor/autoload.php';

var_dump((new ResponseFormatter\ResponseFormatterFactory())->createFormatter());
