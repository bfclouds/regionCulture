<?php
/**
 * Created by PhpStorm.
 * User: Frost Wong <frostwong@gmail.com>
 * Date: 2017/4/25
 * Time: 22:10
 */
use Slim\App;
use PocFramework\Middleware\Log;
use App\Middleware\ExceptionHandlerMiddleware;
echo "a";exit();
if (!defined('APP_ROOT')) {
    define('APP_ROOT', __DIR__ . '/..');
}

header('content-type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Methods:GET,POST,PUT,DELETE,OPTIONS");
header("Access-Control-Allow-Headers:Content-Type");

define('APP_NAME', 'regionalCulture');

require APP_ROOT . '/vendor/autoload.php';

$container = require APP_ROOT . '/src/bootstrap.php';

$app = new App($container);

require APP_ROOT . '/src/routes.php';

$app->add(ExceptionHandlerMiddleware::class)->add(Log::class);

$app->run();