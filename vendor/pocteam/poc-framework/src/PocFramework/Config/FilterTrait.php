<?php
/**
 * User: coderd
 * Date: 2019/4/15
 * Time: 17:24
 */

namespace PocFramework\Config;


trait FilterTrait
{
    protected function filterSize(&$string, $size, $configValue)
    {
        // Do nothing
        if (!isset($configValue) || $configValue < 0) {
            return;
        }

        if ($configValue === 0) {
            $string = "...(truncated:0/$size)";
            return;
        }

        if ($configValue < $size) {
            $string = mb_substr($string, 0, $configValue) . "...(truncated:$configValue/$size)";
        }
    }

    protected function filterRegex(&$string, array $configValue)
    {
        if ($configValue) {
            $string = preg_replace(array_keys($configValue), array_values($configValue), $string);
        }
    }
}