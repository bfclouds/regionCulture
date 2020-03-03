<?php
/**
 * Created by PhpStorm.
 * User: Frost Wong <frostwong@gmail.com>
 * Date: 16/06/2017
 * Time: 14:12
 */

namespace PocFramework\Support\Authorization;


use PocFramework\ConfigInterface;

/**
 * Fetch authorized routes of a registered caller.
 *
 * @package PocFramework\Support\Authorization
 */
class AuthorizedRoutes
{
    private static $routes = [];

    public static function setConfig($config)
    {
        if (is_array($config)) {
            self::$routes = $config;
        } else if ($config instanceof ConfigInterface) {
            self::$routes = $config->get();
        }
    }

    public static function get($ak)
    {
        if (isset(self::$routes[$ak])) {
            return self::$routes[$ak];
        }

        return [];
    }
}