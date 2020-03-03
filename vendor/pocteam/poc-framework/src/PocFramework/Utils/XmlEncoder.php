<?php
/**
 * Created by PhpStorm.
 * User: Frost Wong <frostwong@gmail.com>
 * Date: 23/06/2017
 * Time: 17:49
 */

namespace PocFramework\Utils;


class XmlEncoder
{
    public static function encode($data)
    {
        $result = "";
        foreach ($data as $key=>$value) {
            if (is_numeric($key)) {
                $left = '<Item>';
                $right = '</Item>';
            }else {
                $left = "<$key>";
                $right = "</$key>";
            }

            if (is_array($value)) {
                $result .= $left.self::encode($value).$right;
            }else {
                $result .= "$left$value$right";
            }
        }
        return $result;
    }

}