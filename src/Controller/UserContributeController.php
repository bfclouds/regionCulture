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
class UserContributeController extends BaseController
{
    //添加文章
    public function addArticle(Request $request, Response $response) {
        $parseBody = $request->getParsedBody();

        $rules = [
            'user_id' => 'required|numeric',
            'title' => 'required|string|min:6',
            'content' => 'required|string'
        ];
        $validator = new Validator($parseBody, $rules);
        if($validator->fails()) {
            return $this->apiView->respond([], ApiView::ERROR_PARAM, $validator->getMessage());
        }

        $ret = $this->get(UserContributeService::class)->addArticle($parseBody);
        return $this->apiView->respond($ret);
    }

    //查看我的文章
    public function listArticle(Request $request, Response $response) {
        $params = $request->getParams();
        $rules = [
            'page' => 'required|numeric'
        ];
        $validator = new Validator($params, $rules);
        if($validator->fails()) {
            return $this->apiView->respond([], ApiView::ERROR_PARAM, $validator->getMessage());
        }

        $ret = $this->get(UserContributeService::class)->listArticle($params);
        return $this->apiView->respond(['data' => $ret]);
    }

    //用户投稿列表
    public function listSimple(Request $request, Response $response) {
        $params = $request->getParams();
        $ret = $this->get(UserContributeService::class)->listSimpleByPage($params);
        return $this->apiView->respond(['simple_list' => $ret]);
    }
}
