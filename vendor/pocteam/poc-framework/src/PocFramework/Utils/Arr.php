<?php
/**
 * User: coderd
 * Date: 2019/4/15
 * Time: 17:47
 */

namespace PocFramework\Utils;


class Arr
{
    public static function getFields(array $haystack, array $keys)
    {
        if (empty($haystack) || empty($keys)) {
            return [];
        }

        $values = [];
        foreach ($keys as $key) {
            if (isset($haystack[$key])) {
                $values[$key] = $haystack[$key];
            }
        }

        return $values;
    }
}