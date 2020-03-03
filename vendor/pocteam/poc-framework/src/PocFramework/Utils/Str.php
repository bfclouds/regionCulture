<?php
/**
 * User: coderd
 * Date: 2019/4/17
 * Time: 12:38
 */

namespace PocFramework\Utils;


use Psr\Http\Message\RequestInterface;

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

    public static function formatCurlCommand(RequestInterface $r)
    {
        $segments = ['curl', '-X'];
        $segments[] = $r->getMethod();
        foreach ($r->getHeaders() as $name => $values) {
            foreach ($values as $value) {
                $segments[] = '-H';
                $segments[] = "'$name:$value'";
            }
        }

        $contentType = $r->getHeaderLine('Content-Type');
        if (ContentTypes::isReadable($contentType)) {
            $body = (string)$r->getBody();
            if ($body) {
                $segments[] = '-d';
                $segments[] = "'$body'";
            }
        }

        $uri = (string)$r->getUri();
        $segments[] = "'$uri'";

        return implode(' ', $segments);
    }
}