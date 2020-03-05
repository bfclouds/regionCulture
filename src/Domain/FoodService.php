<?php
/**
 * User: coderd
 * Date: 2018/8/6
 * Time: 18:03
 */

namespace App\Domain;

use App\Data\Table\FoodTable;
use App\Data\Table\RRegionFoodTable;
use Illuminate\Database\Connection;
use PocFramework\Mvc\BaseModel;
use PocFramework\Exception\Http\ApiException;
use App\Lib\ApiView;

/**
 * Class FoodService
 * @package App\Domain
 * @property Connection dbRegional
 */

class FoodService extends BaseModel
{
//    public function addFood($regionId, $params) {
//        try {
//            $this->dbRegional->beginTransaction();
//
//            $id = $this->get(FoodTable::class)->addFood($params);
//            $this->get(RRegionFoodTable::class)->addRFood($regionId, $id);
//
//            $this->dbRegional->commit();
//
//            return $id;
//        }catch (\Exception $e) {
//            throw new ApiException([], ApiView::DATABASE_ERROR);
//        }
//    }

    public function listFoods($params) {
        $foods =  $this->get(FoodTable::class)->listFoods($params);
        $total = $this->get(FoodTable::class)->getCount();
        if (isset($params['per_page'])) {
            $total = ceil($total/$params['per_page']);
        }else {
            $total = ceil($total/3);
        }

        $data = [
            'info' => $foods ?: [],
        ];
        if (!empty($foods)) {
            $data['total_page'] = $total ?: 1;
        }

        return $data ?: [];
    }

    public function addFood($params) {
        $regionId = $params['region_id'];
        $region = $this->get(RegionalService::class)->getRegionName($regionId);

        if (empty($region)) {
            throw new ApiException([], ApiView::SERVER_FAILURE);
        }

        $ret = $this->get(FoodTable::class)->addFood($params, $region);
        return $ret;
    }
}