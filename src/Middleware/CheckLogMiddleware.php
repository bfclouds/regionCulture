<?php
/**
 * User: coderd
 * Date: 2019/4/16
 * Time: 18:14
 */

namespace App\Middleware;

use App\Lib\ApiView;
use Illuminate\Support\Facades\Auth;
use Slim\Http\Request;
use Slim\Http\Response;
use PocFramework\Middleware\BaseMiddleware;


/**
 * Class CheckLogMiddleware
 * @package App\Middleware
 *@property ApiView apiView
 *
 */
class CheckLogMiddleware extends BaseMiddleware
{
    public function __invoke(Request $request, Response $response, $next)
    {
        //dd($_COOKIE);
        if($_COOKIE['user']) {
            return $next($request,$response);
        }else{
            return $this->apiView->respond([],ApiView::AUTH_UNAUTHORIZED);
        }
    }
}