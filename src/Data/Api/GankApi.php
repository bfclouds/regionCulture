<?php
/**
 * Created by PhpStorm.
 * User: Frost Wong <frostwong@gmail.com>
 * Date: 06/06/2017
 * Time: 21:25
 */

namespace App\Data\Api;


use PocFramework\Config\RPC;
use GuzzleHttp\Psr7\Response;
use PocFramework\Support\Rpc\BaseRpc;

class GankApi extends BaseRpc
{
    private static $APIs = [
        'list_benefits' => [
            'path' => '/api/data/福利/{page_size}/{page}',
            'method' => 'get',
            'options' => [
                'connect_timeout' => 1.0,
                'timeout' => 3.0,
                // 仅为了演示自定义过滤RPC请求日志配置用法
                RPC::LOG_RESPONSE_BODY_SIZE => 100,
                // 正则性能不好，慎用
                RPC::LOG_RESPONSE_BODY_REGEX => [
                    '/[0-9]+/' => '[数字]',
                    '/users/' => '[用户]'
                ]
            ]
        ],
    ];

    protected function serviceConfig()
    {
        $apiConfig = $this->ci[GLOBAL_CONFIG]['api'];

        $config = [
            'domain' => $apiConfig['gank']['domain'],
            'host' => $apiConfig['gank']['host'],
            'options' => [
                'connect_timeout' => 1.5,
                'timeout' => 4.0,
                // 仅为了演示自定义过滤RPC请求日志配置用法
                RPC::LOG_RESPONSE_BODY_SIZE => 20,
                // 正则性能不好，慎用
                RPC::LOG_RESPONSE_BODY_REGEX => [
                    '/[0-9]+/' => '[数字]',
                    '/users/' => '[用户]'
                ]
            ]
        ];

        return $config;
    }

    protected function apiList($apiName = '')
    {
        return self::$APIs[$apiName];
    }

    protected function decode(Response $response = null, $code = 200)
    {
        $resp = [
            'status_code' => 0,
            'body' => null,
        ];
        if ($response) {
            $resp = [
                'status_code' => $response->getStatusCode(),
                'body' => json_decode($response->getBody(), true),
            ];
        }

        return $resp;
    }
}