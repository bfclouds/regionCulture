<?php
/**
 * Created by PhpStorm.
 * User: Frost Wong <frostwong@gmail.com>
 * Date: 2017/4/27
 * Time: 13:16
 */

namespace PocFramework\Utils;


class Time
{
    public static function currentTimeInSeconds()
    {
        return time();
    }

    public static function currentTimeInMillis()
    {
        return round(microtime(true) * 1000);
    }

    public static function timestampToInt($time)
    {
        return strtotime($time);
    }

    public static function now()
    {
        return date('yyyy-mm-dd H:i:s');
    }

    public static function intToTimestamp($int)
    {
        return date('Y-m-d', $int);
    }
}