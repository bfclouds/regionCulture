<?php
/**
 * User: coderd
 * Date: 2018/8/20
 * Time: 20:41
 */

namespace PocFramework\Middleware;


use Slim\Http\Request;
use Slim\Http\Response;
use PocFramework\Config\SYSConfig;

class AppKeyMiddleware extends BaseMiddleware
{
    public function __invoke(Request $request, Response $response, $next)
    {
        $appKeyAuth = SYSConfig::instance()->appKeyAuth();
        if ($appKeyAuth === null) {
            throw new \InvalidArgumentException("AppKeyMiddleware: SYSConfig property 'appKeyAuth' must be provided");
        }

        if ($appKeyAuth->check($request) === false) {
            return $response->withStatus(403)
                ->withJson([
                    'errno' => 10011,
                    'errmsg' => 'Permissions denied, please contact manager.',
                ])
                ->withHeader('WWW-Authenticate', sprintf('Basic realm="%s"', 'Protected'));
        }

        $this->ci['appKey'] = $appKeyAuth->appKey();

        return $next($request, $response);
    }
}