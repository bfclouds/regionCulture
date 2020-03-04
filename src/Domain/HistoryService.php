<?php
/**
 * User: coderd
 * Date: 2018/8/6
 * Time: 18:03
 */

namespace App\Domain;


use App\Data\Table\HistoryTable;
use App\Data\Table\RRegionHistoryTable;
use App\Data\Table\UserContributeTable;
use App\Lib\ApiView;
use Illuminate\Database\Connection;
use PocFramework\Exception\Http\ApiException;
use PocFramework\Mvc\BaseModel;


/**
 * Class HistoryService
 * @package App\Domain
 * @property Connection dbRegional
 */

class HistoryService extends BaseModel
{
    public function addHistory($regionId, $params) {
        try {
            $this->dbRegional->beginTransaction();

            $id = $this->get(HistoryTable::class)->addHistory($params);
            $this->get(RRegionHistoryTable::class)->addRHistory($regionId, $id);

            $this->dbRegional->commit();
            return $id;
        }catch (\Exception $e) {
            throw new ApiException([], ApiView::DATABASE_ERROR);
        }
    }
}