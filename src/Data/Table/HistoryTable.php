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
class HistoryTable extends BaseModel
{
    const TABLE = 'history';

    const HISTORY_ID = 'id';         //历史Id
    const HISTORY_TITLE = 'title';     //历史事件名称
    const HISTORY_CONTENT = 'content';     //历史时间内容

    const PAGE_COUNT = '3';

    private static $selectColumns = [
        self::HISTORY_ID,
        self::HISTORY_TITLE,
        self::HISTORY_CONTENT
    ];

    public function addHistory($params) {
        $data = [
            self::HISTORY_TITLE => $params['title'],
            self::HISTORY_CONTENT => $params['content']
        ];
        return $this->dbRegional->table(self::TABLE)
            ->insertGetId($data);
    }

}