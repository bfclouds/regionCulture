<?php
/**
 * User: coderd
 * Date: 2019/4/17
 * Time: 16:05
 */

namespace PocFramework\Middleware;


use Slim\Http\Request;
use Slim\Http\Response;
use PocFramework\Config\Log;
use PocFramework\Config\SYSConfig;

/**
 * Class FilterShortLogMiddleware
 * @package App\Middleware
 */
class LogFilterTruncateMiddleware extends BaseMiddleware
{
    public function __invoke(Request $request, Response $response, $next)
    {
        SYSConfig::instance()->Log()->withValue(Log::HTTP_RESPONSE_BODY_SIZE, 66);

        return $next($request, $response);
    }
}