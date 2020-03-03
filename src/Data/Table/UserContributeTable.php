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
class UserContributeTable extends BaseModel
{
    const TABLE = 'user_contribute';

    const ID = 'id';         //历史Id
    const USER_ID = 'user_id';     //用户id
    const REGION_ID = 'region_id';         //地区id
    const TAG_ID = 'tag_id';     //标签id
    const CONTRIBUTE_TITLE = 'title';    //用户投稿文章标题
    const CONTRIBUTE_CONTENT = 'content';    //用户投稿文章内容
    const CREATED_TIME = 'created_time';       //创建时间

    const PAGE_COUNT = '3';

    private static $selectColumns = [
        self::ID,
        self::REGION_ID,
        self::USER_ID,
        self::TAG_ID,
        self::CONTRIBUTE_TITLE,
        self::CONTRIBUTE_CONTENT
    ];

}