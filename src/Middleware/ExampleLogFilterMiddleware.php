<?php
/**
 * User: coderd
 * Date: 2019/4/16
 * Time: 18:14
 */

namespace App\Middleware;


use Slim\Http\Request;
use Slim\Http\Response;
use PocFramework\Config\Log;
use PocFramework\Config\SYSConfig;
use PocFramework\Middleware\BaseMiddleware;

/**
 * Class LogFilterMiddleware
 * @package App\Middleware
 *
 * 本例子演示自定义过滤请求体&响应体日志内容
 */
class ExampleLogFilterMiddleware extends BaseMiddleware
{
    public function __invoke(Request $request, Response $response, $next)
    {
        $log = SYSConfig::instance()->Log();

        // 自定义请求体日志大小，0 为不记录
        $log->withValue(Log::HTTP_REQUEST_BODY_SIZE, 50);
        // 正则过滤请求体日志内容，正则性能不好，慎用
        $log->withValue(
            Log::HTTP_REQUEST_BODY_REGEX,
            [
                '/[0-9]+/' => '[数字]',
                '/users/' => '[用户]'
            ]
        );

        // 自定义响应体日志大小，0 为不记录
        $log->withValue(Log::HTTP_RESPONSE_BODY_SIZE, 50);
        // 正则过滤响应体日志内容，正则性能不好，慎用
        $log->withValue(
            Log::HTTP_RESPONSE_BODY_REGEX,
            [
                '/[0-9]+/' => '[数字]',
                '/users/' => '[用户]'
            ]
        );

        return $next($request, $response);
    }
}