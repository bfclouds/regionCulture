<?php
/**
 * Created by PhpStorm.
 * User: Frost Wong <frostwong@gmail.com>
 * Date: 08/06/2017
 * Time: 13:26
 */

namespace PocFramework\Utils;


class AcceptLanguage
{
    CONST COOKIE_LANGUAGE_KEY = 'ksc_lang';

    private static $validLanguages = [
        'zh',
        'en',
        'ko',
    ];

    public static function get()
    {
        //todo 先从cookie中获取language类型
        $language = strtolower($_COOKIE[self::COOKIE_LANGUAGE_KEY]);
        if(in_array($language, self::$validLanguages)){
            return $language;
        }

        $al = strtolower(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2));

        if (!in_array($al, self::$validLanguages)) {
            return 'zh';
        }

        return $al;
    }
}