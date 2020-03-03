<?php
/**
 * Created by PhpStorm.
 * User: Frost Wong <frostwong@gmail.com>
 * Date: 02/05/2017
 * Time: 23:07
 */

namespace PocFramework\Support\Rpc\Middleware;


use Psr\Http\Message\RequestInterface;

class Uri
{
    public function __invoke(callable $handler)
    {
        return function (RequestInterface $request, array $options) use ($handler) {
            if (isset($options['attributes'])) {
                $attributes = $options['attributes'];
                $uri = (string)$request->getUri();

                $parsedUri = self::interpolate($uri, $attributes);

                $request = $request->withUri(new \GuzzleHttp\Psr7\Uri($parsedUri), true);
            }
            return $handler($request, $options);
        };
    }

    /**
     * Replace placeholders in uri
     *
     * @param $message
     * @param array $context
     * @return string
     */
    private static function interpolate($message, array $context = array())
    {
        $replace = [];
        foreach ($context as $key => $val) {
            if (!is_array($val) && (!is_object($val) || method_exists($val, '__toString'))) {
                $replace['{' . $key . '}'] = $val;
            }
        }

        return strtr(urldecode($message), $replace);
    }
}