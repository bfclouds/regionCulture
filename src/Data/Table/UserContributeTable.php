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

    const COL_ID = 'id';
    const COL_USER_ID = 'user_id';     //用户id
    const COL_REGION_ID = 'region_id';         //地区id
    const COL_TAG_ID = 'tag_id';     //标签id
    const COL_CONTRIBUTE_TITLE = 'title';    //用户投稿文章标题
    const COL_CONTRIBUTE_CONTENT = 'content';    //用户投稿文章内容
    const COL_IS_ENABLED = 'is_enabled';            // 是否通过审核
    const COL_CREATED_TIME = 'created_time';       //创建时间
    const COL_UPDATED_TIME = 'updated_time';       //修改时间

    const PAGE_COUNT = '3';
    const ENABLED = 1; // 启用，通过审核
    const NOT_ENABLED = 0; // 未启用，没有通过审核

    private static $selectColumns = [
        self::COL_ID,
        self::COL_REGION_ID,
        self::COL_USER_ID,
        self::COL_TAG_ID,
        self::COL_CONTRIBUTE_TITLE,
        self::COL_CONTRIBUTE_CONTENT
    ];

    public function listUserContribute() {
        $cond = [
            self::COL_IS_ENABLED => self::NOT_ENABLED
        ];
        return $this->dbRegional->table(self::TABLE)
            ->where($cond)
            ->get(self::$selectColumns)
            ->toArray();
    }

    public function getUserArticle($params) {
        $cond = [
            self::COL_USER_ID => $params['user_id'],
            self::COL_IS_ENABLED => self::ENABLED
        ];

        return $this->dbRegional->table(self::TABLE)
            ->where($cond)
            ->get(self::$selectColumns)
            ->toArray();
    }

    public function listUserArticles() {
        $cond = [
            self::COL_IS_ENABLED => self::ENABLED
        ];

        return $this->dbRegional->table(self::TABLE)
            ->where($cond)
            ->get(self::$selectColumns)
            ->toArray();
    }

    public function passArticle($params) {
        $cond = [
            self::COL_ID => $params['id']
        ];

        $data = [
            self::COL_IS_ENABLED => self::ENABLED
        ];

        return $this->dbRegional->table(self::TABLE)
            ->where($cond)
            ->update($data);
    }

    public function refuseArticle($params) {
        $cond = [
            self::COL_ID => $params['id']
        ];

        return $this->dbRegional->table(self::TABLE)
            ->where($cond)
            ->delete();
    }
}