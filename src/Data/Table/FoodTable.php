<?php
/**
 * User: coderd
 * Date: 2018/8/6
 * Time: 18:03
 */

namespace App\Data\Table;


use PocFramework\Mvc\BaseModel;
use Illuminate\Database\Connection;

/**
 * Class RegionTable
 * @package App\Data\Table
 * @property Connection dbRegional
 *
 */
class FoodTable extends BaseModel
{
    const TABLE = 'food';

    const FOOD_ID = 'id';         //美食Id
    const FOOD_NAME = 'name';     //美食名称
    const FOOD_SIMPLE = 'simple';         //美食内容
    const FOOD_IMG = 'image';         //  美食图片
    const REGION_ID = 'region_id';
    const REGION_NAME = 'region_name';

    const PER_PAGE = 3;

    private static $selectColumns = [
        self::FOOD_ID,
        self::FOOD_NAME,
        self::FOOD_SIMPLE,
        self::FOOD_IMG,
        self::REGION_ID,
        self::REGION_NAME
    ];

    public function listFoods($params) {
        $page = isset($params['page']) ? $params['page'] : 1;
        $perPage = isset($params['per_page']) ? $params['per_page'] : self::PER_PAGE;

        return $this->dbRegional->table(self::TABLE)
            ->forPage($page, $perPage)
            ->get(self::$selectColumns)
            ->toArray();
    }

    public function getCount() {
        return $this->dbRegional->table(self::TABLE)
            ->count();
    }


}