<?php
/**
 * Created by PhpStorm.
 * User: Frost Wong <frostwong@gmail.com>
 * Date: 8/1/2017
 * Time: 5:39 PM
 */

use PocFramework\Support\Env;
use Ramsey\Uuid\Uuid;

if (!defined('REQUEST_ID')) {
    define('REQUEST_ID', Uuid::uuid4());
}

if (get_cfg_var('ENV') !== false) {
    define('ENV', get_cfg_var('ENV'));
}

if (!defined('ENV')) {
    define('ENV', Env::PBS);
}
define('APP_NAME', 'profile_unittest');
define('APP_ROOT', __DIR__ . '/..');
define('LOG_DIR', __DIR__);
