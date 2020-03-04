<?php
/**
 * Created by PhpStorm.
 * User: Frost Wong <frostwong@gmail.com>
 * Date: 2017/4/27
 * Time: 0:51
 */

namespace PocFramework\Middleware;


use PocFramework\Mvc\AbstractApiView;
use PocFramework\Support\Authorization\AuthorizedRoutes;
use PocFramework\Support\Log;
use PocFramework\Utils\IP;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;
use Slim\Route;

/**
 * @property AbstractApiView $apiView
 */
class BasicAuth extends BaseMiddleware
{
    /**
     * @var Route
     */
    private $route;

    use AttributeTrait;

    public function __invoke(ServerRequestInterface $request, Response $response, callable $next)
    {
        $auth = $request->getHeaderLine('Authorization');

        if (!preg_match("/Basic\s+(.*)$/i", $auth, $matches)) {
            Log::info('auth info must be provided.');
            return $response->withStatus(401)
                ->withHeader('WWW-Authenticate', sprintf('Basic realm="%s"', 'Protected'));
        }

        list($ak, $sk) = explode(':', base64_decode($matches[1]), 2);

        $this->route = $request->getAttribute('route');

        if (!$this->check($ak, $sk)) {
            Log::info('auth info provided does not match', ['ak' => $ak, 'sk' => $sk]);
            return $response->withStatus(401)
                ->withHeader('WWW-Authenticate', sprintf('Basic realm="%s"', 'Protected'));
        }

        $this->ci['app_id'] = $ak;
        $request = self::setAttribute($request, 'appId', $ak);
        $request = self::setAttribute($request, 'clientIp', IP::getClientIP());

        return $next($request, $response);
    }

    private function check($ak, $sk)
    {
        $authConfig = $this->ci[GLOBAL_CONFIG]['basic_auth'];
        if (!isset($authConfig[$ak])) {
            return false;
        }

        if (trim($authConfig[$ak]) !== trim($sk)) {
            return false;
        }

        $name = $this->route->getName();

        return !(AuthorizedRoutes::get($ak) && !\in_array($name, AuthorizedRoutes::get($ak), true));
    }
}