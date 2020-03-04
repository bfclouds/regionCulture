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
class TagTable extends BaseModel
{
    const TABLE = 'region';

    const TAG_ID = 'id';         //标签Id
    const TAG_NAME = 'name';     //标签名称

    private static $selectColumns = [
        self::TAG_ID,
        self::TAG_NAME,
    ];

}