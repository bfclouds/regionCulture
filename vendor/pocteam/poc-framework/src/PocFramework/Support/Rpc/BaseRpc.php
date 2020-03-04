<?php
/**
 * Created by PhpStorm.
 * User: Frost Wong <frostwong@gmail.com>
 * Date: 2017/4/27
 * Time: 15:48
 */

namespace PocFramework\Support\Rpc;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use InvalidArgumentException;
use PocFramework\Config\SYSConfig;
use PocFramework\IoC;
use PocFramework\Support\Log;
use PocFramework\Support\Rpc\Middleware\AwsSignature;
use PocFramework\Support\Rpc\Middleware\Header;
use PocFramework\Support\Rpc\Middleware\Logger;
use PocFramework\Support\Rpc\Middleware\Retry;
use PocFramework\Support\Rpc\Middleware\Uri;
use PocFramework\Utils\Str;

/**
 */
abstract class BaseRpc extends IoC
{
    abstract protected function serviceConfig();

    abstract protected function apiList($apiName = '');

    private $retryOptions = [
        'max' => 0,
        'delay' => 100,
    ];

    protected function prepareOptions($apiName = null)
    {
        $apiOptions = $serviceOptions = [];

        $serviceConfig = $this->serviceConfig();
        $apiConfig = $this->apiList($apiName);

        if (isset($serviceConfig['options'])) {
            $serviceOptions = $serviceConfig['options'];
        }

        if (isset($apiConfig['options'])) {
            $apiOptions = $apiConfig['options'];
        }

        $rpcOptions = SYSConfig::instance()->RPC()->values();

        return $apiOptions + $serviceOptions + $rpcOptions;
    }

    /**
     * This method should be overwritten depending to your needs
     *
     * @param Response $response
     * @param int $code
     * @return mixed
     */
    abstract protected function decode(Response $response = null, $code = 200);

    /**
     * Do http call to remote service
     *
     * @param $apiName
     * @param array $options
     * @return mixed
     * @throws \InvalidArgumentException
     * @throws InvalidApiNameException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function call($apiName, array $options = [])
    {
        $serviceConfig = $this->serviceConfig();
        if (!isset($serviceConfig['domain']) || !isset($serviceConfig['host'])) {
            throw new InvalidArgumentException('ApiResource is not defined');
        }

        $domain = $serviceConfig['domain'];
        $host = $serviceConfig['host'];

        $apiConfig = $this->apiList($apiName);

        if (!isset($apiConfig['method']) || !isset($apiConfig['path'])) {
            throw new InvalidApiNameException('Api name "$apiName" is not defined');
        }
        $method = $apiConfig['method'];
        $path = $apiConfig['path'];

        $clientOptions = $this->prepareOptions($apiName);
        $clientOptions['base_uri'] = $domain;
        $clientOptions['handler'] = $this->getClientHandlerStack($apiName);

        $clientOptions = array_replace_recursive($clientOptions, $options);
        $client = new Client($clientOptions);
        $request = new Request($method, $path, ['Host' => $host]);

        try {
            $response = $client->send($request);
            $statusCode = $response->getStatusCode();
        } catch (ClientException $e) {
            $response = $e->getResponse();
            $statusCode = $response->getStatusCode();
        } catch (ServerException $e) {
            $response = $e->getResponse();
            $statusCode = $response->getStatusCode();
        } catch (ConnectException $e) {
            $response = null;
            $statusCode = 0;
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $statusCode = $response->getStatusCode();
            } else {
                $response = null;
                $statusCode = 0;
            }
        } catch (\Exception $e) {
            $response = null;
            $statusCode = 0;

            $line = '[RPC] ' . $request->getMethod() . ' ' . $request->getRequestTarget()
                . '|host:' . $host
                . '|options:' . json_encode($options, JSON_UNESCAPED_UNICODE)
                . '|exception:' . Str::exceptionToStringWithoutLF($e);
            Log::error($line);
        }

        return $this->decode($response, $statusCode);
    }

    private function getClientHandlerStack($apiName)
    {
        $serviceConfig = $this->serviceConfig();
        $retryOptions = $serviceConfig['retry'] ?? $this->retryOptions;
        Log::debug('retry options', $retryOptions);

        $apiConfig = $this->apiList($apiName);
        if (isset($apiConfig['retry'])) {
            $retryOptions = $apiConfig['retry'] + $retryOptions;
        }
        $handlerStack = HandlerStack::create();
        $retryMiddleware = Middleware::retry(Retry::decider($retryOptions['max']), Retry::delay($retryOptions['delay']));
        $middlewares = [
            $retryMiddleware,
            new Uri(),
            new Header(),
            new AwsSignature(),
            new Logger(),
        ];

        foreach ($middlewares as $middleware) {
            $handlerStack->push($middleware);
        }

        return $handlerStack;
    }
}