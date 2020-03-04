<?php
/**
 * User: coderd
 * Date: 2018/8/21
 * Time: 17:42
 */

namespace App\Config;


use Slim\Container;
use App\Lib\ApiView;
use App\Lib\Database\Redis;
use PocFramework\Support\DB\DB;
use PocFramework\Config\SYSConfig;
use PocFramework\Support\Authorization\AppKeyBasicAuth;

class Initializer
{
    public static function initAppKeyConfig(Container $container)
    {
        $appKeyBasicAuth = new AppKeyBasicAuth();
        $appKeyBasicAuth->withAppKeys($container[GLOBAL_CONFIG]['app_key']['app_keys']);
        $appKeyBasicAuth->withPermissions($container[GLOBAL_CONFIG]['app_key']['permissions']);
        SYSConfig::instance()->withAppKeyAuth($appKeyBasicAuth);
    }

    public static function initInternalProperty(Container $container)
    {
        $container['dbRegional'] = function (Container $container) {
            return DB::makePdo($container[GLOBAL_CONFIG]['database']['regional']);
        };

        $container['redisCache'] = function (Container $container) {
            return new Redis($container[GLOBAL_CONFIG]['redis']['default']);
        };

        $container['apiView'] = function (Container $container) {
            return new ApiView($container->response);
        };
    }
}