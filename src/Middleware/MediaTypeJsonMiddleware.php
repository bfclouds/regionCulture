<?php
/**
 * User: coderd
 * Date: 2018/9/18
 * Time: 14:37
 */

namespace App\Middleware;


use App\Lib\ApiView;
use Slim\Http\Request;
use Slim\Http\Response;
use PocFramework\Middleware\BaseMiddleware;

class MediaTypeJsonMiddleware extends BaseMiddleware
{
    const MEDIA_TYPE_JSON                = 'application/json';

    public function __invoke(Request $request, Response $response, $next)
    {
        if (!$this->checkIfJsonMediaType($request->getMethod(), $request->getMediaType())) {
            return $this->apiView->respond(
                [],
                ApiView::UNSUPPORTED_MEDIA_TYPE,
                self::MEDIA_TYPE_JSON
            );
        }

        return $next($request, $response);
    }

    private function checkIfJsonMediaType($method, $mediaType)
    {
        return !(
            $mediaType !== self::MEDIA_TYPE_JSON
            && in_array($method, ['POST', 'PUT', 'PATCH'], true)
        );
    }
}