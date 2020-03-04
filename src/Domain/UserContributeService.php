<?php
/**
 * User: coderd
 * Date: 2018/8/6
 * Time: 18:03
 */

namespace App\Domain;


use App\Data\Table\UserContributeTable;
use PocFramework\Exception\Http\ApiException;
use App\Lib\ApiView;
use PocFramework\Mvc\BaseModel;

/**
 * Class UserContributeService
 * @package App\Domain
 */

class UserContributeService extends BaseModel
{
    public function listUserContribute() {
        $ret = $this->get(UserContributeTable::class)->listUserContribute();
        return $ret ?: [];
    }

    public function getUserArticle($params) {
        $ret = $this->get(UserContributeTable::class)->getUserArticle($params);
        return $ret ?: [];
    }

    public function listUserArticles() {
        $ret = $this->get(UserContributeTable::class)->listUserArticles();
        return $ret ?: [];
    }

    public function passArticle($params) {
        return $this->get(UserContributeTable::class)->passArticle($params);
    }

    public function refuseArticle($params) {
        return $this->get(UserContributeTable::class)->refuseArticle($params);
    }
}