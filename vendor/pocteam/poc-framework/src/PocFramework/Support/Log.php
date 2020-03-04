<?php
/**
 * Created by PhpStorm.
 * User: Frost Wong <frostwong@gmail.com>
 * Date: 02/05/2017
 * Time: 18:00
 */

namespace PocFramework\Support;


use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use PocFramework\Config\Log as LogConfig;
use PocFramework\Utils\ContentTypes;
use PocFramework\Utils\IP;
use PocFramework\Utils\Timer;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use PocFramework\Config\SYSConfig;

/**
 * Class Log
 * @method static emergency($message, array $context = [])
 * @method static alert($message, array $context = [])
 * @method static critical($message, array $context = [])
 * @method static error($message, array $context = [])
 * @method static warning($message, array $context = [])
 * @method static notice($message, array $context = [])
 * @method static info($message, array $context = [])
 * @method static debug($message, array $context = [])
 * @method static log($level, $message, array $context = [])
 * @package PocFramework\Support
 */
class Log
{
    /**
     * @var LoggerInterface
     */
    private static $logger;
    private static $logLevel = Logger::INFO;

    /**
     * Sets minimum logging level at which this handler will be triggered.
     *
     * @param int $logLevel
     */
    public static function setLogLevel($logLevel = Logger::INFO)
    {
        self::$logLevel = $logLevel;
    }

    /**
     * Get logging level at which this handler will be triggered.
     *
     * @return int
     */
    public static function getLogLevel(): int
    {
        return self::$logLevel;
    }

    public static function getLogger(): LoggerInterface
    {
        return self::$logger;
    }

    /**
     * Call monolog's methods via this magic method.
     *
     * @param $name
     * @param $arguments
     */
    public static function __callStatic($name, $arguments)
    {
        self::setUpLogger();
        \call_user_func_array([self::$logger, $name], $arguments);
    }

    /**
     * Record request and response in one line.
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     */
    public static function request(RequestInterface $request, ResponseInterface $response)
    {
        $logConfig = SYSConfig::instance()->Log();

        $clientIP = IP::getClientIP();

        $logRequestString = self::formatRequestLog($logConfig, $request, $clientIP);
        $logResponseString = self::formatResponseLog($logConfig, $response);

        $logLine = '[REQUEST] ' . $logRequestString . ' [RESPONSE] ' . $logResponseString;

        if ($response->getStatusCode() < 500) {
            self::info($logLine);
        } else {
            self::error($logLine);
        }
    }

    private static function formatRequestLog(LogConfig $logConfig, RequestInterface $request, $clientIP)
    {
        $bodySize = $request->getBody()->getSize();
        $requestContentType = $request->getHeaderLine('Content-Type');

        $logRequestBody = '';
        if (ContentTypes::isReadable($requestContentType)) {
            $logRequestBody = (string)$request->getBody();
            $logConfig->filterRequestBody($logRequestBody, mb_strlen($logRequestBody));
        }

        $logRequestString = $request->getMethod() . ' ' . $request->getHeaderLine('Host') . $request->getRequestTarget()
            . '|request_body_size:' . $bodySize
            . '|request_body:' . $logRequestBody
            . '|request_headers:' . json_encode($request->getHeaders(), JSON_UNESCAPED_UNICODE)
            . '|client_ip:' . $clientIP;

        return $logRequestString;
    }

    private static function formatResponseLog(LogConfig $logConfig, ResponseInterface $response)
    {
        $cost = Timer::stop('request');

        $responseBody = $response->getBody();
        $responseBodySize = $responseBody->getSize();

        $responseContentType = $response->getHeaderLine('Content-Type');
        $logResponseBody = '';
        if (ContentTypes::needLog($responseContentType)) {
            $logResponseBody = (string)$responseBody;
            if (self::getLogLevel() <= Logger::NOTICE) {
                $logConfig->filterResponseBody($logResponseBody, mb_strlen($logResponseBody));
            }
        }

        return 'response_status_code:' . $response->getStatusCode()
            . '|response_body_size:' . $responseBodySize
            . '|response_body:' . $logResponseBody
            . '|response_headers:' . json_encode($response->getHeaders(), JSON_UNESCAPED_UNICODE)
            . '|cost:' . $cost;
    }

    private static function setUpLogger()
    {
        if (self::$logger === null) {
            $logger = new Logger(APP_NAME);
            $errorLogHandler = (new RotatingFileHandler(LOG_DIR . '/app.log'))->setLevel(self::$logLevel);
            $formatterWithRequestId = new LineFormatter(
                '[%datetime%] [' . REQUEST_ID . "] %channel%.%level_name%: %message% %context% %extra%\n",
                LineFormatter::SIMPLE_DATE,
                false,
                true
            );
            $errorLogHandler->setFormatter($formatterWithRequestId);
            $logger->pushHandler($errorLogHandler);
            self::$logger = $logger;
        }
    }

}