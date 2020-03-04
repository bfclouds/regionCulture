<?php

namespace App\Config;

class Redis {
    public static function get() {
        return Conf::$redis;
    }
}