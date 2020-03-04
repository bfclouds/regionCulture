<?php
/**
 * User: coderd
 * Date: 2017/7/21
 * Time: 16:42
 */

namespace PocFramework\Support\Rpc\Middleware;


use Psr\Http\Message\RequestInterface;
use PocFramework\Support\Rpc\Auth\AwsSignatureV4;

class AwsSignature
{
    public function __invoke(callable $handler)
    {
        /**
         * @param RequestInterface $request
         * @param array $options   $options['aws_signature'] in this format:
         *                          [
         *                              '__version' => 4,
                                        'region' => 'cn-shanghai-3',
                                        'service' => 'iam',
                                        'ak' => '***',
                                        'sk' => '***',
         *                          ]
         */
        return function (RequestInterface $request, array $options) use ($handler) {
            if (isset($options['aws_signature']) && (int)$options['aws_signature']['__version'] === 4) {
                $v4 = new AwsSignatureV4();
                $awsSignature = $options['aws_signature'];
                $request = $v4->signRequest($request, $awsSignature);
            }

            return $handler($request, $options);
        };
    }
}