<?php
/**
 * User: coderd
 * Date: 2018/10/11
 * Time: 19:49
 */

namespace App\Middleware;


use App\Lib\ApiView;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Lib\Support\Str;
use PocFramework\Middleware\BaseMiddleware;
use PocFramework\Exception\Http\ApiException;

/**
 * Class ExceptionHandlerMiddleware
 * @package App\Middleware
 * @property ApiView $apiView
 */
class ExceptionHandlerMiddleware extends BaseMiddleware
{
    public function __invoke(Request $request, Response $response, $next)
    {
        try {
            $response = $next($request, $response);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }

        return $response;
    }

    private function handleException(\Exception $e)
    {
        if ($e instanceof ApiException) {
            return $this->apiView->respond(
                $e->getData(),
                $e->getCode(),
                ...$e->getParams()
            );
        }

        $code = ApiView::INTERNAL_SERVER_ERROR;
        if ($e instanceof \PDOException) {
            $code = ApiView::DATABASE_ERROR;
        } else if ($e instanceof \RedisException) {
            $code = ApiView::CACHE_ERROR;
        }

        trigger_error(Str::exceptionToString($e), E_USER_WARNING);

        return $this->apiView->respond([], $code);
    }
}