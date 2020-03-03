<?php
/**
 * Created by PhpStorm.
 * User: Frost Wong <frostwong@gmail.com>
 * Date: 19/06/2017
 * Time: 11:06
 */

namespace PocFramework\Utils;

/**
 * Content-Type related functions
 *
 * @package PocFramework\Utils
 */
class ContentTypes
{
    private static $readableContentTypes = [
        'application/json',
        'application/xml',
        'application/x-www-form-urlencoded',
        'text/html',
        'text/plain',
    ];

    private static $needLogContentTypes = [
        'application/json',
        'application/xml',
        'application/x-www-form-urlencoded',
    ];

    /**
     * Show all readable content types
     *
     * @return array
     */
    public static function getReadable()
    {
        return self::$readableContentTypes;
    }

    /**
     * Check if a content type is readable, help logger decide whether record it or not
     *
     * @param $contentType
     * @return bool
     */
    public static function isReadable($contentType)
    {
        if (\is_array($contentType)) {
            $c = $contentType[0];
        } else {
            $c = explode(';', $contentType)[0];
        }

        return \in_array($c, self::$readableContentTypes, true);
    }

    public static function needLog($contentType)
    {
        if (\is_array($contentType)) {
            $c = $contentType[0];
        } else {
            $c = explode(';', $contentType)[0];
        }

        return \in_array($c, self::$needLogContentTypes, true);
    }
}