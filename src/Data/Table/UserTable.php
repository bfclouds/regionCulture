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
 * Class UserTable
 * @package App\Data\Table
 * @property Connection dbRegional
 *
 */
class UserTable extends BaseModel
{
    const TABLE = 'user';

    const COL_ID = 'id';
    const COL_EMAIL = 'email';
    const COL_NAME = 'name';
    const COL_PASSWORD = 'passwd';
    const COL_REGION = 'region';
    const COL_AVATAR = 'avatar';
    const COL_REGISTER_TIME = 'register_time';

    private static $selectColumns = [
        self::COL_ID,
        self::COL_EMAIL,
        self::COL_NAME,
        self::COL_PASSWORD,
        self::COL_REGION,
        self::COL_AVATAR
        ];


    public function addUser($registerInfo) {
        if(empty($registerInfo)) {
            return false;
        }

        $registerInfo[self::COL_REGISTER_TIME] = date("Y-m-d H:i:s");

        return $this->dbRegional->table(self::TABLE)->insertGetId($registerInfo);
    }

    public function  checkUserInfo($email, $userName) {
        $query = $this->dbRegional->table(self::TABLE);
        if($email) {
            $query->orWhere(self::COL_EMAIL,'=', $email);
        }
        if ($userName) {
            $query->orWhere(self::COL_NAME, '=',  $userName );
        }

        return $query->get(self::$selectColumns)->first();
    }
}