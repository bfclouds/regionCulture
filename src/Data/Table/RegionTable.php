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
class RegionTable extends BaseModel
{
    const TABLE = 'region';

    const REGION_ID = 'id';         //地区Id
    const REGION_NAME = 'name';     //地区名字
    const REGION_STANDING = 'standing'; // 行政区
    const REGION_SIMPLE = 'simple';     //地区简介
    const REGION_IMAGE = 'image';     //地区简介
    const REGION_CONTENTS = 'contents';   //详细地址

    const PER_PAGE = 3;  //分页一页数据条数

    const LIMIT_COUNT_SEARCH = 33;  // 搜索部分数据条数

    const IMAGE_DEFAULT = 'https://ss1.bdstatic.com/70cFvXSh_Q1YnxGkpoWK1HF6hhy/it/u=2616303771,2788535072&fm=26&gp=0.jpg';

    private static $selectColumns = [
        self::REGION_ID,
        self::REGION_NAME,
        self::REGION_STANDING,
        self::REGION_SIMPLE,
        self::REGION_IMAGE,
        self::REGION_CONTENTS,
    ];

    private static $selectSimpleColumns = [
        self::REGION_ID,
        self::REGION_NAME,
        self::REGION_STANDING,
        self::REGION_SIMPLE,
        self::REGION_IMAGE
    ];

    private static $selectSearchColumns = [
        self::REGION_ID,
        self::REGION_NAME
    ];

    public function searchByName($name) {
        $cond = [
            self::REGION_NAME => $name
        ];

        return $this->dbRegional->table(self::TABLE)
            ->where($cond)
            ->get(self::$selectSearchColumns)
            ->first();
    }

    public function addContents($regionName, $standing, $simple, $contents) {
        $data = [
            self::REGION_NAME => $regionName,
            self::REGION_SIMPLE => $simple,
            self::REGION_STANDING => $standing,
            self::REGION_CONTENTS => $contents
        ];

        return $this->dbRegional->table(self::TABLE)
            ->insertGetId($data);
    }

    public function getRegionDetail($id) {
        $cond = [
            self::REGION_ID => $id
        ];

        return $this->dbRegional->table(self::TABLE)
            ->where($cond)
            ->get(self::$selectColumns)
            ->first();
    }

    public function listRegion($page) {
        $page = isset($page) ? $page : 1;
        return $this->dbRegional->table(self::TABLE)
            ->forPage($page, self::PER_PAGE)
            ->get(self::$selectSimpleColumns)
            ->toArray();
    }

    public function listSearch() {
        return $this->dbRegional->table(self::TABLE)
            ->limit(self::LIMIT_COUNT_SEARCH)
            ->get(self::$selectSearchColumns)
            ->toArray();
    }

    public function getCount() {
        return $this->dbRegional->table(self::TABLE)
            ->count();
    }

}