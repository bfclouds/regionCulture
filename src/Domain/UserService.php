<?php
/**
 * User: coderd
 * Date: 2018/8/6
 * Time: 18:13
 */

namespace App\Domain;


use App\Lib\ApiView;
use App\Lib\Database\Redis;
use App\Data\Table\UserTable;
use PocFramework\Mvc\BaseModel;
use PocFramework\Exception\Http\ApiException;
use Psr\Container\ContainerInterface;

/**
 * Class UserService
 * @package App\Domain
 */

class UserService extends BaseModel
{
    const LOGIN_EXPIRE = 3600;      //登录过期时间
    private $support;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        $this->support = $this->get(SupportService::class);
    }

    public function login($loginInfo) {
        $email = trim($loginInfo['userEmail']);
        $password = trim($loginInfo['password']);

        $userInfo = $this->checkUserInfo($email);
        if (!$userInfo) {
            throw new ApiException([], ApiView::PASSPORT_USER_NOT_EXIST);
        }

        if ($userInfo['passwd'] != $password) {
            throw new ApiException([], ApiView::PASSPORT_PASSWORD_ERROR);
        }

        unset($userInfo['passwd']);

        $token = $this->get(SupportService::class)->saveLoginToken($userInfo);
        if ($token) {
            $userInfo['token'] = $token;
        }else {
            throw new ApiException([], ApiView::SERVER_FAILURE);
        }

        return $userInfo;
    }

    /**
     * @param $registerInfo
     * @throws ApiException
     */
    public function register($registerInfo) {
        $userName = trim($registerInfo['userName']);
        $email = trim($registerInfo['userEmail']);

        $isExistence = $this->checkUserInfo($email, $userName);

        if ($isExistence) {
            throw new ApiException([], ApiView::PASSPORT_USEREMAIL_USERNAME_EXIST);
        }

        $data = [
            'name' => $userName,
            'passwd' => trim($registerInfo['password']),
            'email' => $email
        ];
        $ret = $this->get(UserTable::class)->addUser($data);
        if ($ret) {
            return true;
        }

        return false;
    }

    //注册,登录的时候检测用户是否存在（username,email）
    public function checkUserInfo($email, $userName=null) {
        $ret = $this->get(UserTable::class)->checkUserInfo($email, $userName);
        return $ret ?? [];
    }

    public function logout($token) {
        $ret = $this->get(SupportService::class)->isLogin($token);
        if (!$ret) {
            throw new ApiException([], ApiView::AUTH_UNAUTHORIZED);
        }
        $ret = $this->get(SupportService::class)->loginOut($token);
        if (!$ret) {
            throw new ApiException([], ApiView::SERVER_FAILURE);
        }
        return true;
    }

    //发送验证码
    public function sendVerification($userInfo) {
        $userName = $userInfo['userName'];
        $email = $userInfo['userEmail'];
        $code = $this->support->getVerificationCode(); //生成验证码

        if (!$code) {
            throw new ApiException([], ApiView::SERVER_FAILURE);
        }
        //验证一分钟验证码发送次数
        $count = $this->get(SupportService::class)->checkCodeLimit($email);

        $ret = $this->support->setCodeRedis($email, $code, $count);           //保存验证码和申请验证码次数
        if (!$ret) {
            throw new ApiException([], ApiView::SERVER_FAILURE);
        }

        $retSendE = $this->support->sendEmail($email, $userName, $code);    //发送邮件
        if (!$retSendE) {
            throw new ApiException([], ApiView::SERVER_FAILURE);
        }
        return true;
    }
}