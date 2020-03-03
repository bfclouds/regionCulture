<?php
/**
 * Created by PhpStorm.
 * User: Frost Wong <frostwong@gmail.com>
 * Date: 06/06/2017
 * Time: 21:24
 */

namespace App\Controller;


use App\Domain\UserContributeService;
use App\Lib\ApiView;
use PocFramework\Support\Validation\Validator;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Domain\UserService;
use PocFramework\Mvc\BaseController;


/**
 * @property ApiView apiView
 */
class UserController extends BaseController
{
    //登录
    public function login(Request $request, Response $response) {
        $paramBody = $request->getParsedBody();

        $rules = [
            'userEmail' => 'required|email',
            'password' => 'required|string'
        ];

        $validator = new Validator($paramBody, $rules);
        if($validator->fails()) {
            return $this->apiView->respond([], ApiView::ERROR_PARAM, $validator->getMessage());
        }

        $ret = $this->get(UserService::class)->login($paramBody);
        return $this->apiView->respond($ret);
    }

    //注册
    public function register(Request $request, Response $response) {
        $paramBody = $request->getParsedBody();
        //captcha,验证码在中间件检验
        $rules = [
            'userEmail' => 'required|email',
            'password' => 'required|string',
            'userName' => 'required|string'
        ];

        $validator = new Validator($paramBody, $rules);
        if($validator->fails()) {
            return $this->apiView->respond([$validator->getMessage()], ApiView::ERROR_PARAM);
        }

        $this->get(UserService::class)->register($paramBody);
        return $this->apiView->respond([]);
    }

    //退出
    public function logout(Request $request, Response $response) {
        //TODO 获取token
        $token = $request->getHeaders();
        $this->get(UserService::class)->logout($token);
    }

    //发送验证码
    public function sendVerificationCode(Request $request, Response $response) {
        $params = $request->getQueryParams();
        $rules = [
            'userEmail' => 'required|email'
        ];

        $validator = new Validator($params, $rules);
        if($validator->fails()) {
            return $this->apiView->respond([], ApiView::ERROR_PARAM, $validator->getMessage());
        }

        $this->get(UserService::class)->sendVerification($params);
        return $this->apiView->respond([]);
    }
}
