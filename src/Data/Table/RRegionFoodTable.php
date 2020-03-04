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
 * Class RRegionFoodTable
 * @package App\Data\Table
 * @property Connection dbRegional
 *
 */
class RRegionFoodTable extends BaseModel
{
    const TABLE = 'r_region_food';

    const COL_ID = 'id';
    const COL_FOOD_ID = 'food_id';
    const COL_REGION_ID = 'region_id';

    private static $selectColumns = [
        self::COL_ID,
        self::COL_FOOD_ID,
        self::COL_REGION_ID
    ];

    public function addRFood($regionId, $foodId) {
        $data = [
            self::COL_FOOD_ID => $foodId,
            self::COL_REGION_ID => $regionId
        ];
        return $this->dbRegional->table(self::TABLE)
            ->insertGetId($data);
    }

    public function getRRegionFood($foodId) {
        $cond = [
            self::COL_FOOD_ID => $foodId
        ];

        return $this->dbRegional->table(self::TABLE)
            ->where($cond)
            ->get(self::$selectColumns)
            ->toArray();
    }

}