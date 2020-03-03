<?php
/**
 * Created by PhpStorm.
 * User: Frost Wong <frostwong@gmail.com>
 * Date: 2017/4/27
 * Time: 0:51
 */

namespace PocFramework\Middleware;


use Monolog\Logger;
use Slim\Http\Response;
use PocFramework\Utils\Timer;
use PocFramework\Support\Log as SupportLog;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @property Logger logger
 */
class Log extends BaseMiddleware
{
    public function __invoke(ServerRequestInterface $request, Response $response, callable $next)
    {
        Timer::start('request');
        $newResponse = $next($request, $response);

        SupportLog::request($request, $newResponse);

        return $newResponse;
    }
}