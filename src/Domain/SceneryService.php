<?php
/**
 * User: coderd
 * Date: 2018/8/6
 * Time: 18:03
 */

namespace App\Domain;


use App\Data\Table\SceneryTable;
use App\Lib\ApiView;
use Illuminate\Database\Connection;
use PocFramework\Exception\Http\ApiException;
use PocFramework\Mvc\BaseModel;
use App\Data\Table\RRegionSceneryTable;

/**
 * Class SceneryService
 * @package App\Domain
 * @property Connection dbRegional
 */
class SceneryService extends BaseModel
{
    public function addScenery($regionId, $params) {
        try{
            $this->dbRegional->beginTransaction();
            $id = $this->get(SceneryTable::class)->addScenery($params);
            $this->get(RRegionSceneryTable::class)->addRScenery($regionId, $id);
            $this->dbRegional->commit();
            return $id;
        }catch (\Exception $e) {
            throw new ApiException([], ApiView::DATABASE_ERROR);
        }
    }
}