<?php
/**
 * Created by PhpStorm.
 * User: wulin
 * Date: 17/7/7
 * Time: 14:21
 */

namespace App\Middleware;

use PocFramework\Exception\Http\ApiException;
use App\Lib\ApiView;
use PocFramework\Middleware\BaseMiddleware;
use PocFramework\Support\Log;
use Slim\Http\Request;

/**
 * Class ExceptionHandler
 *
 * 异常收集处理类
 * @property ApiView apiView
 **/
class ExceptionHandler extends BaseMiddleware
{

    public function __invoke(Request $request, $response, $next)
    {
        try {
            $response = $next($request, $response);
        } catch(ApiException $e){
            $code   = $e->getCode();
            $params = $e->getParams();
            $response = $this->apiView->respond([], $code, ...$params);
            $msg = ['err_code' => $code, 'file' => $e->getFile(), 'line' => $e->getLine()];
            Log::warning('ApiException ', $msg);

        } catch (\Exception $e){
            $response = $this->apiView->respond([], ApiView::INTERNAL_SERVER_ERROR);
            $msg = [
                'err_code' => $e->getCode(),
                'err_msg'  => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ];
            Log::warning('Unknow Exception', $msg);
        }
        return $response;
    }
}