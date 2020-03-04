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
class ArticleController extends BaseController
{
    // 获取许审核用户文章
    public function listUserContributes(Request $request, Response $response) {
        $ret = $this->get(UserContributeService::class)->listUserContribute();
        return $this->apiView->respond($ret);
    }

    public function listUserArticles(Request $request, Response $response) {
        $ret = $this->get(UserContributeService::class)->listUserArticles();
        return $this->apiView->respond($ret);
    }

    // 获取用户文章
    public function getUserArticle(Request $request, Response $response) {
        $params = $request->getQueryParams();
        $rules = [
            'user_id' => 'required|numeric|min:1'
        ];

        $validator = new Validator($params, $rules);
        if($validator->fails()) {
            return $this->apiView->respond([], ApiView::ERROR_PARAM, $validator->getMessage());
        }

        $ret = $this->get(UserContributeService::class)->getUserArticle($params);
        return $this->apiView->respond($ret);
    }

    public function passArticle(Request $request, Response $response) {
        $params = $request->getParsedBody();
        $rules = [
            'id' => 'required|numeric|min:1'
        ];

        $validator = new Validator($params, $rules);
        if($validator->fails()) {
            return $this->apiView->respond([], ApiView::ERROR_PARAM, $validator->getMessage());
        }

        $ret = $this->get(UserContributeService::class)->passArticle($params);
        return $this->apiView->respond($ret);
    }

    public function refuseArticle(Request $request, Response $response) {
        $params = $request->getParsedBody();
        $rules = [
            'id' => 'required|numeric|min:1'
        ];

        $validator = new Validator($params, $rules);
        if($validator->fails()) {
            return $this->apiView->respond([], ApiView::ERROR_PARAM, $validator->getMessage());
        }

        $ret = $this->get(UserContributeService::class)->refuseArticle($params);
        return $this->apiView->respond($ret);
    }
}
