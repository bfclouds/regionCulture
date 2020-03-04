<?php
/**
 * Created by PhpStorm.
 * User: Frost Wong <frostwong@gmail.com>
 * Date: 06/06/2017
 * Time: 21:24
 */

namespace App\Controller;


use App\Domain\RegionalService;
use App\Lib\ApiView;
use PocFramework\Support\Validation\Validator;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Domain\UserService;
use PocFramework\Mvc\BaseController;


/**
 * @property ApiView apiView
 */
class RegionalController extends BaseController
{
    // 添加地区
    public function addRegion(Request $request, Response $response) {
        $params = $request->getParsedBody();

        $rules = [
            'name' => 'required|string',
            'simple' => 'required|string',
            'historys' => 'string',
            'foods' => 'string',
            'scenerys' => 'string'
        ];

        $validator = new Validator($params, $rules);
        if($validator->fails()) {
            return $this->apiView->respond([$validator->getMessage()], ApiView::ERROR_PARAM);
        }

        $res = $this->get(RegionalService::class)->addRegion($params);
        return $this->apiView->respond($res);
    }

    //获取通過id数据信息
    public function getRegionSimple(Request $request, Response $response) {
        $params = $request->getParams();

        $rules = [
            'id' => 'required|numeric'
        ];

        $validator = new Validator($params, $rules);
        if($validator->fails()) {
            return $this->apiView->respond([$validator->getMessage()], ApiView::ERROR_PARAM);
        }

        $ret = $this->get(RegionalService::class)->getRegionDetail($params['id']);
        $data = [
            'info' => $ret
        ];
        return $this->apiView->respond($data);
    }

    //通过页数获取地区信息
    public function listSimple(Request $request, Response $response) {
        $params = $request->getParams();
        $rules = [
            'page' => 'numeric'
        ];

        $validator = new Validator($params, $rules);
        if($validator->fails()) {
            return $this->apiView->respond([$validator->getMessage()], ApiView::ERROR_PARAM);
        }

        $ret = $this->get(RegionalService::class)->listRegion($params['page']);
        return $this->apiView->respond($ret);
    }

    //搜索
    public function searchRegion(Request $request, Response $response) {
        $params = $request->getParams();
        $rules = [
            "name" => "required|string"
        ];
        $validator = new Validator($params, $rules);
        if($validator->fails()) {
            return $this->apiView->respond([$validator->getMessage()], ApiView::ERROR_PARAM);
        }

        $ret = $this->get(RegionalService::class)->searchByName($params['name']);
        $data = [
            'info' => $ret
        ];
        return $this->apiView->respond($data);
    }

    // 搜索框的列表
    public function listHot(Request $request, Response $response) {
        $ret = $this->get(RegionalService::class)->listHot();
        $data = [
            'info' => $ret
        ];
        return $this->apiView->respond($data);
    }

    // 初始化
    public function initInfo(Request $request, Response $response) {
        $this->get(RegionalService::class)->initInfo();
        return $this->apiView->respond([]);
    }
}
