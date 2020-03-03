<?php
/**
 * Created by PhpStorm.
 * User: Frost Wong <frostwong@gmail.com>
 * Date: 2017/4/25
 * Time: 22:11
 */

use App\Config\Initializer;
use PocFramework\Support\Validation\Validator;
use PocFramework\Support\Validation\CustomMessages;

$container = require __DIR__ . '/init.php';
$container[GLOBAL_CONFIG] = require __DIR__ . '/Config/configs.php';

Initializer::initInternalProperty($container);

// Init app key basic auth config
Initializer::initAppKeyConfig($container);

// Init global message config
Validator::setGlobalMessageInstance(new CustomMessages());

return $container;