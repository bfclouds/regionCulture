<?php
/**
 * Created by PhpStorm.
 * User: Frost Wong <frostwong@gmail.com>
 * Date: 06/06/2017
 * Time: 21:24
 */

namespace App\Controller;


use App\Domain\HistoryService;
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
class HistoryController extends BaseController
{
    public function listSimple(Request $request, Response $response) {
        $params = $request->getParams();
        $rules = [
            'page' => 'numeric'
        ];
        $validator = new Validator($params, $rules);
        if($validator->fails()) {
            return $this->apiView->respond([], ApiView::ERROR_PARAM, $validator->getMessage());
        }

        $ret = $this->get(HistoryService::class)->listHistory($params);
        return $this->apiView->respond($ret);
    }

    public function listDetail(Request $request, Response $response) {
        $params = $request->getParams();
        $rules = [
            'id' => 'required|numeric'
        ];
        $validator = new Validator($params, $rules);
        if($validator->fails()) {
            return $this->apiView->respond([], ApiView::ERROR_PARAM, $validator->getMessage());
        }
        $ret = $this->get(HistoryService::class)->listDetail($params);
        return $this->apiView->respond($ret);
    }
}
