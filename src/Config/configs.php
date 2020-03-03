<?php
/**
 * User: coderd
 * Date: 2018/8/6
 * Time: 17:22
 */

$config = [];

$path = __DIR__.'/'.ENV;

$config['common'] = require $path . '/common.php';
$config['app_key'] = require $path . '/app_key.php';
$config['api'] = require $path.'/api.php';
$config['database'] = require $path.'/database.php';
$config['redis'] = require $path.'/redis.php';
return $config;
