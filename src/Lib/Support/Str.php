<?php
/**
 * User: coderd
 * Date: 2018/8/6
 * Time: 17:14
 */

namespace App\Lib\Support;


class Str
{

    public static function endsWith($haystack, $needles)
    {
        foreach ((array) $needles as $needle) {
            if ((string) $needle === substr($haystack, -strlen($needle))) {
                return true;
            }
        }

        return false;
    }

    public static function trimArray($data)
    {
        if (!is_array($data)) {
            return $data;
        }

        foreach ($data as $k => $v) {
            if (is_string($v)) {
                $data[$k] = $v;
            }
        }

        return $data;
    }

    public static function exceptionToString(\Exception $e)
    {
        return 'Unhandled exception \'' . get_class($e)  .'\' with message \'' . $e->getMessage() . '\''
            . ' in ' . $e->getFile() . ':' . $e->getLine() . "\nStack trace:\n" . $e->getTraceAsString();
    }

    public static function exceptionToStringWithoutLF(\Exception $e)
    {
        return 'Unhandled exception \'' . get_class($e)  .'\' with message \'' . $e->getMessage() . '\''
            . ' in ' . $e->getFile() . ':' . $e->getLine();
    }

    /**
     * The cache of studly-cased words.
     *
     * @var array
     */
    protected static $studlyCache = [];

    /**
     * Convert a value to studly caps case.
     *
     * @param  string  $value
     * @return string
     */
    public static function studly($value)
    {
        $key = $value;

        if (isset(static::$studlyCache[$key])) {
            return static::$studlyCache[$key];
        }

        $value = ucwords(str_replace(['-', '_'], ' ', $value));

        return static::$studlyCache[$key] = str_replace(' ', '', $value);
    }

    public static function uuid()
    {
        $chars = md5(microtime() . uniqid(mt_rand(), true));
        $segments = [
            substr($chars, 0, 8),
            substr($chars, 8, 4),
            substr($chars, 12, 4),
            substr($chars, 16, 4),
            substr($chars, 20, 12)
        ];

        return implode('-', $segments);
    }

    private static $randSeeds = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    public static function randomChars($num)
    {
        $seedLen = strlen(self::$randSeeds);

        $result = '';
        for ($i = 0; $i < $num; $i++) {
            $result .= self::$randSeeds[mt_rand(0, $seedLen - 1)];
        }

        return $result;
    }

    /**
     * @param $subject
     * @param array $searchReplaceValues
     *              [
     *                  'search' => 'replace',
     *                  ...
     *              ]
     * @return mixed
     */
    public static function replace($subject, array $searchReplaceValues)
    {
        return str_replace(array_keys($searchReplaceValues), array_values($searchReplaceValues), $subject);
    }

    /**
     * @param $subject
     * @param $replaceChar
     * @param $start
     * @param null $length
     * @return string
     */
    public static function erase($subject, $replaceChar, $start, $length = null)
    {
        $subjectLen = strlen($subject);
        if ($start >= $subjectLen) {
            return $subject;
        }
        if ($start < 0) {
            $start = $subjectLen + $start;
        }
        if ($start < 0) {
            $start = 0;
        }

        $headStr = substr($subject, 0, $start);

        if ($length === null) {
            $tailStrStartPos = $subjectLen;
        } else if ($length < 0) {
            $tailStrStartPos = $subjectLen + $length;
        } else {
            $tailStrStartPos = $start + $length;
        }

        if ($tailStrStartPos < $start) {
            return $subject;
        }

        $tailStr = substr($subject, $tailStrStartPos);
        $middleStr = str_repeat($replaceChar, $tailStrStartPos - $start);

        return $headStr . $middleStr . $tailStr;
    }

    public static function eraseWithBefore($subject, $replaceChar, $start, $beforeSeparator, $length = null)
    {
        if (empty($subject)) {
            return $subject;
        }

        if (strpos($subject, $beforeSeparator) !== false) {
            list($segment1, $segment2) = explode($beforeSeparator, $subject, 2);
            return self::erase($segment1, $replaceChar, $start, $length) . $beforeSeparator . $segment2;
        }

        return self::erase($subject, $replaceChar, $start, $length);
    }
}