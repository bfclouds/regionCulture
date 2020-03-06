<?php
/**
 * User: coderd
 * Date: 2018/8/6
 * Time: 18:13
 */

namespace App\Domain;

use App\Lib\ApiView;
use App\Support\AESUtil;
use PocFramework\Exception\Http\ApiException;
use PocFramework\Mvc\BaseModel;
use App\Lib\Database\Redis;
use App\Lib\Support\Email;

/**
 * Class SupportService
 * @package App\Domain
 * @property Redis redisCache
 */

class SupportService extends BaseModel
{
    const SEND_EMAIL_FROM = '834298507@qq.com';
    const SEND_EMAIL_FROM_NAME = '地区文化';
    const SEND_EMAIL_SUBJECT = '地区文化验证码';
    const REDIS_VERIFICATION_CODE = 'code_';        //验证码前缀
    const REDIS_VERIFICATION_CODE_TIME = 'code_time_';        //验证码次数
    const LOGIN_EXPIRE = 3600;                      //过期时间
    const CODE_EXPIRE = 180;                        //验证码3分钟失效
    const ONE_M_SEND_TIME = 60;                     //验证码一分钟最多发3次
    const TMP_TOKEN_PREFIX = 'tmp_login_token_';  //token前缀

    public function sendEmail($sendTo, $userName, $sendMessage, $sendFrom = self::SEND_EMAIL_FROM, $sendFromName = self::SEND_EMAIL_FROM_NAME) {
//        $ret = SimpleMail::make()->setTo($sendTo, $userName)
//            ->setFrom($sendFrom, $sendFromName)
//            ->setSubject(self::SEND_EMAIL_SUBJECT)
//            ->setMessage($sendMessage)
//            ->send();

        $ret = $this->get(Email::class)->email($sendTo, $userName, $sendMessage);
        return $ret ?? false;
    }

    //生成随记验证码
    public function getVerificationCode() {
        return rand(1000,9999);
    }

    public function setCodeRedis($email, $code, $count=0) {
        $ret = $this->redisCache->set(self::REDIS_VERIFICATION_CODE . $email, json_encode($code), self::CODE_EXPIRE);//验证码存redis
        $this->redisCache->set(self::REDIS_VERIFICATION_CODE_TIME . $email, $count+1 ,self::ONE_M_SEND_TIME);           //一分钟申请验证码次数存redis
        return $ret ?? false;
    }


    //保存登录token
    public function saveLoginToken($userInfo) {
        $token = AESUtil::randomHash();
        $ret = $this->redisCache->set(self::TMP_TOKEN_PREFIX . $token, json_encode($userInfo), self::LOGIN_EXPIRE);
        if ($ret !== false) {
            return $token;
        }
        return [];
    }

    //获取key对应的value值
    public function attempts($key)
    {
        $ret = $this->redisCache->get($key);
        return $ret;
    }

    //检查发送验证码限制
    public function checkCodeLimit($email) {
        $count = $this->attempts(self::REDIS_VERIFICATION_CODE_TIME . $email);
        if (!empty($count) && $count >=3) {
            throw new ApiException([], ApiView::SEND_EMAIL_LIMIT);
        }
        return $count ?: 0;
    }

    //检验验证码
    public function checkCaptcha($email, $captcha) {
        $redisCaptcha = $this->attempts(self::REDIS_VERIFICATION_CODE . $email);

        return (int)$redisCaptcha == (int)$captcha ? true : false;
    }

    //检查是否登录
    public function isLogin($token) {
        $ret = $this->redisCache->myGet($token);
        return $ret ?? false;
    }

    //退出登录
    public function loginOut($token) {
        $ret = $this->redisCache->myDelete($token);
        return $ret ?? false;
    }
}