<?php

namespace App\Config;


class Conf
{
    // Configs from environment-config-file
    public static $common = [];
    public static $database = [];
    public static $redis = [];
    public static $ldap = [];
    public static $api = [];

    public static $basicAuth = [];
    public static $authorizedRoutes = [];
    
    // Configs easy to use
    public static $domain;

    private static $isInitialized = false;

    public static function init()
    {
        if (self::$isInitialized) {
            return;
        }

        self::$common = require __DIR__ . '/' . ENV . '/common.php';
        // These must be initialized here, because they will be used later.
        self::$domain = self::$common['domain'];

        self::$database = require __DIR__ . '/' . ENV . '/database.php';
        self::$redis = require __DIR__ . '/' . ENV . '/redis.php';
        self::$api = require __DIR__ . '/' . ENV . '/api.php';

        self::$isInitialized = true;
    }

    public static function isKsyun()
    {
        return self::$common['company_id'] === 'ksyun';
    }

    public static function supportOa()
    {
        return self::$common['support_oa'] == '1';
    }
}
