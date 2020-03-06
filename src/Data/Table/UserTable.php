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
    const COL_PASSWORD = 'password';
    const COL_AVATAR = 'avatar';
    const COL_PHONE = 'phone';
    const COL_REGION = 'region';
    const COL_IS_ADMIN = 'is_admin';
    const COL_CREATED_TIME = 'created_time';
    const COL_UPDATED_TIME = 'updated_time';

    private static $selectColumns = [
        self::COL_ID,
        self::COL_EMAIL,
        self::COL_NAME,
        self::COL_PASSWORD,
        self::COL_REGION,
        self::COL_AVATAR,
        self::COL_PHONE,
        self::COL_IS_ADMIN,
        ];


    public function addUser($registerInfo, $userName, $email) {
        $data = [
            self::COL_NAME => $userName,
            self::COL_PASSWORD => trim($registerInfo['password']),
            self::COL_EMAIL => $email,
        ];

        $data[self::COL_CREATED_TIME] = $data[self::COL_UPDATED_TIME] = time();

        return $this->dbRegional->table(self::TABLE)->insertGetId($data);
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

    public function updateUser($params) {
        $cond = [
            self::COL_ID => $params['id']
        ];
        $data = [];
        if (isset($params['name'])) {
            $data[self::COL_NAME] = $params['name'];
        }
        if (isset($params['phone'])) {
            $data[self::COL_PHONE] = $params['phone'];
        }
        if (isset($params['region'])) {
            $data[self::COL_REGION] = $params['region'];
        }

        return $this->dbRegional->table(self::TABLE)
            ->where($cond)
            ->update($data);
    }

    public function getUser($id) {
        $cond = [
            self::COL_ID => $id
        ];

        return $this->dbRegional->table(self::TABLE)
        ->where($cond)
            ->get(self::$selectColumns)
            ->first();
    }
}