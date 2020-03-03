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
class UserFocusTable extends BaseModel
{
    const TABLE = 'region';

    const ID = 'id';         //关注Id
    const USER_ID = 'user_id';      //用户id
    const REGION_ID = 'region_id';         //地区id

    private static $selectColumns = [
        self::ID,
        self::USER_ID,
        self::REGION_ID
    ];

}