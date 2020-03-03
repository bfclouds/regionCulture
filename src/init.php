<?php
/**
 * Created by PhpStorm.
 * User: Frost Wong <frostwong@gmail.com>
 * Date: 2017/4/25
 * Time: 23:19
 */

/**
 * !!! IMPORTANT !!!
 *
 * DON'T TOUCH THIS FILE UNLESS YOU ARE CLEAR WHAT YOU ARE DOING.
 *
 * 请不要修改这个文件除非你真的知道你在做什么
 */

use PocFramework\Handler\PhpError;
use PocFramework\Support\Env;
use PocFramework\Support\Session;
use Ramsey\Uuid\Uuid;
use Slim\Container;

if (!defined('APP_NAME') || !defined('APP_ROOT')) {
    throw new Exception('APP_NAME and APP_ROOT must be set');
}

if (isset($_SERVER['HTTP_X_KSC_REQUEST_ID'])) {
    define('REQUEST_ID', $_SERVER['HTTP_X_KSC_REQUEST_ID']);
}

if (!defined('REQUEST_ID') && isset($_SERVER['HTTP_X_Request_Id'])) {
    define('REQUEST_ID', $_SERVER['HTTP_X_Request_Id']);
}

if (!defined('REQUEST_ID')) {
    define('REQUEST_ID', Uuid::uuid4());
}

if (get_cfg_var('ENV') !== false) {
    define('ENV', get_cfg_var('ENV'));
}

if (!defined('ENV')) {
    define('ENV', Env::DEV);
}

if (!defined('GLOBAL_CONFIG')) {
    define('GLOBAL_CONFIG', 'global_config');
}

if (!defined('LOG_DIR')) {
    define('LOG_DIR', '/data/log/apps/' . APP_NAME);
}

if (!defined('CONFIG_DIR')) {
    define('CONFIG_DIR', APP_ROOT . '/src/Config');
}

if (!defined('JSON_DIR')) {
    define('JSON_DIR', APP_ROOT . '/src/Json');
}

if (!defined('SESSION_KEY')) {
    define('SESSION_KEY', 'digest');
}

$container = new Container([
    'settings' => [
        'displayErrorDetails' => false,
        'determineRouteBeforeAppMiddleware' => true,
    ],
]);

$container['phpErrorHandler'] = function (Container $c) {
    return new PhpError();
};

$container['errorHandler'] = function (Container $c) {
    return new PhpError();
};

$container['session'] = function (Container $c) {
    return new Session();
};

return $container;