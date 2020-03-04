<?php
/**
 * Created by PhpStorm.
 * User: Frost Wong <frostwong@gmail.com>
 * Date: 06/06/2017
 * Time: 21:43
 */

namespace App\Lib;


use PocFramework\Mvc\AbstractOpenApiView;

class OpenApiView extends AbstractOpenApiView
{
    const PARAMETERS_ERROR              = 'InvalidParameterValue';
    const IP_DENIED                     = 'AccessDeniedForIp';
    const SIGNATURE_VERIFICATION_FAILED = 'SignatureVerificationFailed';
    const TIMESTAMP_VERIFICATION_FAILED = 'TimestampVerificationFailed';
    const ACTION_NOT_FOUND              = 'ActionNotFound';

    protected $codeMsgs = [
        self::PARAMETERS_ERROR => [400,
            [
                'en' => 'Parameters error: %s',
                'zh' => '参数错误：%s',
            ]
        ],
        self::IP_DENIED => [403,
            [
                'en' => 'Access denied for ip: %s',
                'zh' => 'Ip访问受限：%s',
            ]
        ],
        self::SIGNATURE_VERIFICATION_FAILED => [400,
            [
                'en' => 'Signature verification failed',
                'zh' => '签名校验失败',
            ]
        ],
        self::TIMESTAMP_VERIFICATION_FAILED => [400,
            [
                'en' => 'Timestamp verification failed',
                'zh' => 'Timestamp参数校验失败',
            ]
        ],
        self::ACTION_NOT_FOUND => [404,
            [
                'en' => 'Action (%s) not found',
                'zh' => '动作（%s）不存在',
            ]
        ],
    ];
}