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
class RRegionHistoryTable extends BaseModel
{
    const TABLE = 'r_region_history';

    const COL_ID = 'id';
    const COL_HISTORY_ID = 'history_id';         //历史Id
    const COL_REGION_ID = 'region_id';

    private static $selectColumns = [
        self::COL_ID,
        self::COL_HISTORY_ID,
        self::COL_REGION_ID
    ];

    public function addRHistory($regionId, $historyId) {
        $data = [
            self::COL_HISTORY_ID => $historyId,
            self::COL_REGION_ID => $regionId
        ];
        return $this->dbRegional->table(self::TABLE)
            ->insertGetId($data);
    }

}