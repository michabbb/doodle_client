<?php

require_once __DIR__.'/../vendor/autoload.php';

$shortopts .= 'u:p:';  // Required value
$container = new \Pimple\Container();

$container['config'] = parse_ini_file('config.ini', true);
