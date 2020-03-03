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
class SceneryTable extends BaseModel
{
    const TABLE = 'scenery';

    const SCENERY_ID = 'id';         //景点Id
    const REGION_ID = 'region_id';         //地区id
    const SCENERY_TITLE = 'title';     //景点名称
    const SCENERY_CONTENT = 'content';     //景点内容

    private static $selectColumns = [
        self::SCENERY_ID,
        self::REGION_ID,
        self::SCENERY_TITLE,
        self::SCENERY_CONTENT
    ];

}