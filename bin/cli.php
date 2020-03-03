<?php
/**
 * Created by PhpStorm.
 * User: Frost Wong <frostwong@gmail.com>
 * Date: 20/06/2017
 * Time: 15:50
 */

use PocFramework\Handler\CliPhpError;
use PocFramework\Support\CLI\App;

if (!defined('APP_ROOT')) {
    define('APP_ROOT', __DIR__ . '/..');
}

define('APP_NAME', 'poc-app');

require APP_ROOT . '/vendor/autoload.php';

$requestUri = '/' . ltrim($argv[1], '/');

$cliApp = '\App\Command\\' . trim($argv[1]);
if (!class_exists($cliApp)) {
    echo 'Please check if class ' . $cliApp . ' exists', "\n";
    exit;
}

$container = require APP_ROOT . '/src/bootstrap.php';
$container['cliErrorHandler'] = function ($container) {
    return new CliPhpError();
};

$app = new App($container, $cliApp);

$app->run();
