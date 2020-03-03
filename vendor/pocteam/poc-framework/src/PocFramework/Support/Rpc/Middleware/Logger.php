<?php
/**
 * Created by PhpStorm.
 * User: Frost Wong <frostwong@gmail.com>
 * Date: 2017/4/27
 * Time: 17:01
 */

namespace PocFramework\Support\Rpc\Middleware;


use PocFramework\Utils\Str;
use Psr\Log\LoggerInterface;
use PocFramework\Utils\Timer;
use PocFramework\Support\Log;
use PocFramework\Config\SYSConfig;
use PocFramework\Utils\ContentTypes;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Logger
{
    /**
     * @var LoggerInterface
     */
    private static $rpcLogger;

    /**
     * @var \GuzzleHttp\Promise\FulfilledPromise|\GuzzleHttp\Promise\RejectedPromise;
     */
    private $promise;

    public function __invoke(callable $handler)
    {
        return function (RequestInterface $request, array $options)
        use ($handler) {
            Timer::start('rpc');
            $this->promise = $handler($request, $options);
            $cost = Timer::stop('rpc');

            return $this->promise->then(
                static function (ResponseInterface $response) use ($request, $options, $cost) {

                    $req = self::logRequest($request);
                    $res = self::logResponse($response, $options);
                    $log = array_merge($req, $res, ['cost:' . $cost]);
                    $line = '[RPC] ' . implode('|', $log);
                    if ((int)$response->getStatusCode() >= 500) {
                        Log::error($line);
                    } else if ((int)$response->getStatusCode() >= 300) {
                        Log::warning($line);
                    } else {
                        Log::info($line);
                    }

                    return $response;
                },
                static function ($reason) use ($request, $cost) {
                    if (!($reason instanceof \Exception)) {
                        throw new \RuntimeException(
                            'Guzzle\Middleware\Logger: unknown error reason: '
                            . (is_object($reason) ? get_class($reason) : (string)$reason)
                        );
                    }

                    $req = self::logRequest($request);
                    $log = array_merge($req, ['cost:' . $cost]);
                    $line = '[RPC] ' . implode('|', $log);
                    $line .= '|exception: ' . Str::exceptionToStringWithoutLF($reason);
                    Log::error($line);

                    throw $reason;
                }
            );
        };
    }

    protected static function logRequest(RequestInterface $r): array
    {
        return [
            'curl_cmd:' . Str::formatCurlCommand($r)
        ];
    }

    protected static function logResponse(ResponseInterface $response, $options): array
    {
        $contentType = $response->getHeaderLine('Content-Type');
        $data = [
            'response_status_code:' . $response->getStatusCode(),
        ];

        $responseBody = '';
        if (ContentTypes::isReadable($contentType)) {
            $responseBody = (string)$response->getBody();
            if (Log::getLogLevel() <= \Monolog\Logger::NOTICE) {
                SYSConfig::instance()->RPC()->filterResponseBody(
                    $responseBody,
                    mb_strlen($responseBody),
                    $options
                );
            }
        }
        $data[] = 'response_body:' . $responseBody;

        return $data;
    }
}