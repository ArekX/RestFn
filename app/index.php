<?php

use ArekX\JsonQL\Helpers\DI;
use ArekX\JsonQL\Rest\Config;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/vendor/autoload.php';

$config = require_once __DIR__ . '/Config/config.php';
$params = require_once __DIR__ . '/Config/params.php';

DI::bootstrap(new Config($config, $params));