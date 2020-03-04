<?php
/**
 * User: coderd
 * Date: 2019/4/16
 * Time: 18:14
 */

namespace App\Middleware;

use App\Lib\ApiView;
use Illuminate\Support\Facades\Auth;
use PocFramework\Exception\Http\ApiException;
use Slim\Http\Request;
use Slim\Http\Response;
use PocFramework\Middleware\BaseMiddleware;
use App\Domain\SupportService;


/**
 * Class CheckLogMiddleware
 * @package App\Middleware
 *@property ApiView apiView
 *
 */
class CheckCodeMiddleware extends BaseMiddleware
{
    public function __invoke(Request $request, Response $response, $next)
    {
        $params = $request->getParsedBody();
        $email = $params['userEmail'];  //邮箱
        $code = $params['captcha'];        //验证码

        if (sizeof($code) > 4) {
            throw new ApiException([], ApiView::SEND_CAPTCHA_ERROR);
        }
        $ret = $this->get(SupportService::class)->checkCaptcha($email, $code);

        if (!$ret) {
            throw new ApiException([], ApiView::SEND_CAPTCHA_ERROR);
        }

        return $next($request, $response);
    }
}