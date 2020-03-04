<?php
/**
 * Created by PhpStorm.
 * User: Frost Wong <frostwong@gmail.com>
 * Date: 02/05/2017
 * Time: 18:17
 */

namespace PocFramework\Support\Rpc\Middleware;


use Psr\Http\Message\RequestInterface;

class Header
{
    public function __invoke(callable $handler)
    {
        return function (
            RequestInterface $request,
            array $options
        ) use ($handler) {
            $request = $request->withHeader('X-Request-Id', REQUEST_ID);
            return $handler($request, $options);
        };
    }
}