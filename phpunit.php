<?php

//require __DIR__.'/vendor/autoload.php';
require __DIR__.'/../../../vendor/autoload.php';

define('ROOT_PATH', realpath(__DIR__ . '/../../../'));
define('APP_PATH', ROOT_PATH . '/app');

require_once ROOT_PATH . '/resources/bootstrap.php';

date_default_timezone_set('UTC');
