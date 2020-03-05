<?php
/**
 * Created by PhpStorm.
 * User: Frost Wong <frostwong@gmail.com>
 * Date: 06/06/2017
 * Time: 21:24
 */

namespace App\Controller;

use App\Domain\FoodService;
use App\Lib\ApiView;
use PocFramework\Support\Validation\Validator;
use Slim\Http\Request;
use Slim\Http\Response;
use PocFramework\Mvc\BaseController;


/**
 * @property ApiView apiView
 */
class FoodController extends BaseController
{
    public function listFoods(Request $request, Response $response) {
        $params = $request->getParams();
        $rules = [
            'page' => 'numeric'
        ];
        $validator = new Validator($params, $rules);
        if($validator->fails()) {
            return $this->apiView->respond([], ApiView::ERROR_PARAM, $validator->getMessage());
        }

        $ret = $this->get(FoodService::class)->listFoods($params);
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
        $ret = $this->get(FoodService::class)->listDetail($params);
        return $this->apiView->respond($ret);
    }

    public function addFood(Request $request, Response $response) {
        $params = $request->getParsedBody();
        $rules = [
            'region_id' => 'required|numeric|min:1',
            'name' => 'required|string',
            'content' => 'required|string'
        ];
        $validator = new Validator($params, $rules);
        if($validator->fails()) {
            return $this->apiView->respond([], ApiView::ERROR_PARAM, $validator->getMessage());
        }

        $ret = $this->get(FoodService::class)->addFood($params);
        return $this->apiView->respond($ret);
    }
}
