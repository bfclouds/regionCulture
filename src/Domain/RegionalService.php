<?php
/**
 * User: coderd
 * Date: 2018/8/6
 * Time: 18:13
 */

namespace App\Domain;


use App\Controller\RegionalController;
use App\Data\Table\DialectTable;
use App\Data\Table\HistoryTable;
use App\Data\Table\RegionTable;
use App\Data\Table\SceneryTable;
use App\Data\Table\UserContributeTable;
use App\Domain\FoodService;
use App\Lib\ApiView;
use App\Lib\Database\Redis;
use Illuminate\Database\Connection;
use Monolog\Handler\IFTTTHandler;
use PocFramework\Mvc\BaseModel;
use PocFramework\Exception\Http\ApiException;
use Psr\Container\ContainerInterface;
use function GuzzleHttp\Promise\queue;

/**
 * Class RegionalService
 * @package App\Domain
 * @property Connection dbRegional
 */

class RegionalService extends BaseModel
{
    private $regionalTable;
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        $this->regionalTable = $this->get(RegionTable::class);
    }

//    public function addRegion($params) {
//        $name = $params['name'];
//        $simple = $params['simple'];
//        $res = $this->searchByName($name);
//        if (empty($res)) {
//            throw new ApiException([], ApiView::SERVER_FAILURE);
//        }
//
//        $regionId = $this->regionalTable->addRegion($name, $simple);
//        // 历史
//        if (isset($params['historys'])) {
//            $historys = $params['historys'];
//            foreach ($historys as $history) {
//                $this->get(HistoryService::class)->addHistory($regionId, $history);
//            }
//        }
//        // 美食
//        if (isset($params['foods'])) {
//            $foods = $params['foods'];
//            foreach ($foods as $food) {
//                $this->get(FoodService::class)->addFood($regionId, $food);
//            }
//        }
//        // 景色
//        $scenerys = $params['scenerys'];
//        foreach ($scenerys as $scenery) {
//            $this->get(SceneryService::class)->addScenery($regionId, $scenery);
//        }
//
//        return $regionId;
//    }

    public function initInfo() {
        $path = JSON_DIR;
        if (is_dir($path)) {
            $fileArr = scandir($path);
            try {
                $this->dbRegional->beginTransaction();
                for ($i = 2; $i < count($fileArr); $i++) {
                    $file = file_get_contents($path . '/' . $fileArr[$i]);

                    $file = json_decode($file, true);

                    $regionName = $file['first-title'];
                    $standing = $file['second-title'];
                    $simple = $file['regionIntroduce'];
                    $contents = json_encode($file['contents']);

                    $res = $this->searchByName($regionName);

                    if (empty($res)) {
                        $this->addContents($regionName, $standing, $simple, $contents);
                    }
                }
                $this->dbRegional->commit();
                return true;
            }catch (\Exception $e) {
                throw new ApiException([], ApiView::DATABASE_ERROR);
            }
        }

        return true;
    }

    public function addContents($regionName, $standing, $simple, $contents) {
        return $this->regionalTable->addContents($regionName, $standing, $simple, $contents);
    }

    public function searchByName($regionName) {
        return $this->regionalTable->searchByName($regionName);
    }

    public function getRegionDetail($id) {
        $res = $this->regionalTable->getRegionDetail($id);
        $i = 0;
        $j = 0;
        if (isset($res['contents'])) {
            $res['contents'] = json_decode($res['contents'], true);
        }else {
            $res['contents'] = [];
        }
        foreach ($res['contents'] as &$content) {
//            print_r($content);
            $content['id'] = 'k1' . $i;
            if (!empty($content['content'])) {
                foreach ($content['content'] as &$co) {
                    $co['id'] = 'k2' . $j;
                    $j++;
                }
            }
            $i++;
        }

        return $res;
    }

    public function listRegion($page) {
        $regions = $this->regionalTable->listRegion($page);
        if (isset($regions)) {
            foreach ($regions as &$region) {
                if (empty($region['image'])) {
                    $region['image'] = RegionTable::IMAGE_DEFAULT;
                }
            }
        }
        $total = 0;
        if(!empty($regions)) {
            $total = $this->regionalTable->getCount();
            $total = ceil($total/3);
        }
        $data = [
            'info' => $regions ?: []
        ];
        if (!empty($regions)) {
            $data['total_page'] = $total ?: 1;
        }
        return $data ?: [];
    }

    public function listHot() {
        return  $this->regionalTable->listSearch();
    }

    public function listRegionNames() {
        $ret = $this->regionalTable->listRegionNames();
        return $ret ?: [];
    }

    public function getRegionName($id) {
        $ret = $this->regionalTable->getRegionName($id);
        return $ret ?: [];
    }
}