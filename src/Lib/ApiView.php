<?php
/**
 * Created by PhpStorm.
 * User: Frost Wong <frostwong@gmail.com>
 * Date: 06/06/2017
 * Time: 21:43
 */

namespace App\Lib;


use PocFramework\Mvc\AbstractApiView;

class ApiView extends AbstractApiView
{
    // 1开头的1**** 错误码为所有项目通用
    const PARAMETERS_ERROR       = 10001;
    const UNSUPPORTED_MEDIA_TYPE = 10002;
    const INTERNAL_SERVER_ERROR  = 10005;
    const DATABASE_ERROR         = 10006;
    const CACHE_ERROR            = 10007;
    const UPSTREAM_ERROR         = 10008;

    const AUTH_UNAUTHORIZED               = 10010;
    const AUTH_RESOURCE_PERMISSION_DENIED = 10011;

    const ERROR_PARAM = 40002;//参数错误
    const SERVER_FAILURE = 40003;


    const PASSPORT_USERNAME_EXIST = 20020;
    const PASSPORT_USEREMAIL_EXIST = 20021;
    const PASSPORT_USER_NOT_EXIST = 20022;
    const PASSPORT_PASSWORD_ERROR = 20023;
    const SEND_CAPTCHA_ERROR = 20024;

    const SEND_EMAIL_LIMIT = 20040;
    const PASSPORT_USEREMAIL_USERNAME_EXIST = 20041;
    const DOUBLE_RESOURCE = 20042;


    protected $codeMsgs = [
        self::PARAMETERS_ERROR => [400,
            [
                'en' => 'Parameters error: %s',
                'zh' => '参数错误：%s',
            ]
        ],
        self::UNSUPPORTED_MEDIA_TYPE => [415,
            [
                'en' => 'Unsupported media type, allowed: %s',
                'zh' => '媒体类型不支持，允许类型为：%s',
            ]
        ],
        self::INTERNAL_SERVER_ERROR => [500,
            [
                'en' => 'Service Unavailable(' . self::INTERNAL_SERVER_ERROR . ')',
                'zh' => '服务繁忙，请稍后再试(' . self::INTERNAL_SERVER_ERROR . ')',
            ]
        ],
        self::DATABASE_ERROR => [500,
            [
                'en' => 'Service Unavailable(' . self::DATABASE_ERROR . ')',
                'zh' => '服务繁忙，请稍后再试(' . self::DATABASE_ERROR . ')',
            ]
        ],
        self::CACHE_ERROR => [500,
            [
                'en' => 'Service Unavailable(' . self::CACHE_ERROR . ')',
                'zh' => '服务繁忙，请稍后再试(' . self::CACHE_ERROR . ')',
            ]
        ],
        self::UPSTREAM_ERROR => [500,
            [
                'en' => 'Service Unavailable(' . self::UPSTREAM_ERROR . ')',
                'zh' => '服务繁忙，请稍后再试(' . self::UPSTREAM_ERROR . ')',
            ]
        ],


        self::AUTH_UNAUTHORIZED => [401,
            [
                'en' => 'Unauthorized',
                'zh' => '请先登录',
            ]
        ],
        self::AUTH_RESOURCE_PERMISSION_DENIED => [403,
            [
                'en' => 'Resource permission denied',
                'zh' => '资源访问受限',
            ]
        ],
        self::ERROR_PARAM => [
            400,
            [
                'en' => 'Parameter error',
                'zh' => '参数错误'
            ]
        ],
        self::SERVER_FAILURE => [
            500,
            [
                'en' => 'server failure',
                'zh' => '服务异常',
            ]
        ],
        self::PASSPORT_USERNAME_EXIST => [
            400,
            [
                'en' => 'username %s already exists',
                'zh' => '用户名 %s 已存在',
            ]
        ],
        self::PASSPORT_USEREMAIL_EXIST => [
            400,
            [
                'en' => 'userEmail %s already exists',
                'zh' => '该邮箱 %s 已存在',
            ]
        ],
        self::PASSPORT_USER_NOT_EXIST => [
            200,
            [
                'en' => 'user is not exists',
                'zh' => '该用户不存在',
            ]
        ],
        self::PASSPORT_PASSWORD_ERROR => [
            200,
            [
                'en' => 'userName or password error',
                'zh' => '用户名密码错误',
            ]
        ],
        self::SEND_EMAIL_LIMIT => [
            200,
            [
                'en' => 'lease wait one minute',
                'zh' => '请等待一分钟后再申请验证码'
            ]
        ],
        self::SEND_CAPTCHA_ERROR => [
            200,
            [
                'en' => 'captcha error',
                'zh' => '验证码错误'
            ]
        ],
        self::PASSPORT_USEREMAIL_USERNAME_EXIST => [
            200,
            [
                'en' => 'userEmail or userName already exists',
                'zh' => '该邮箱或用户名已存在',
            ]
        ],
        self::DOUBLE_RESOURCE => [
            400,
            [
                'en' => 'the region was existed!',
                'zh' => '该地区已存在',
            ]
        ]
    ];
}