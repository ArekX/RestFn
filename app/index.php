<?php

use ArekX\JsonQL\Rest\Config;

require_once __DIR__ . '/../vendor/autoload.php';

$config = require_once __DIR__ . '/Config/config.php';
$params = require_once __DIR__ . '/Config/params.php';

define('APP_DIR', __DIR__);

(new Config($config, $params))->bootstrap();