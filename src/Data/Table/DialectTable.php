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
class DialectTable extends BaseModel
{
    const TABLE = 'dialect';

    const FOOD_ID = 'id';         //方言Id
    const FOOD_NAME = 'name';     //方言名称
    const REGION_ID = 'region_id';         //地区Id


    private static $selectColumns = [
        self::FOOD_ID,
        self::FOOD_NAME,
        self::REGION_ID
    ];


}