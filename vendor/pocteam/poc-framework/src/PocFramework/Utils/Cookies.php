<?php
/**
 * Created by PhpStorm.
 * User: Frost Wong <frostwong@gmail.com>
 * Date: 8/11/2017
 * Time: 3:02 PM
 */

namespace PocFramework\Utils;


use GuzzleHttp\Cookie\CookieJar;
use PocFramework\Support\Log;

class Cookies
{
    public static function format(array $cookies, $domain = '.ksyun.com'): CookieJar
    {
        $cookieParams = [];
        foreach ($cookies as $key => $value) {
            $cookieParams[$key] = urlencode($value);
        }

        Log::debug('cookies', $cookieParams);
        return CookieJar::fromArray($cookieParams, $domain);
    }
}