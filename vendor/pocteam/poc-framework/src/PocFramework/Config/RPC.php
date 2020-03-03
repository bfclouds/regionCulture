<?php
/**
 * User: coderd
 * Date: 2019/4/15
 * Time: 14:38
 */


namespace PocFramework\Config;

/**
 * Class RPC
 * @package PocFramework\Config
 */
class RPC extends ConfigBase
{
    use FilterTrait;

    const CONNECT_TIMEOUT = 'connect_timeout';
    const TIMEOUT         = 'timeout';

    const LOG_RESPONSE_BODY_SIZE  = 'log.response.body.size';
    const LOG_RESPONSE_BODY_REGEX = 'log.response.body.regex';

    private static $defaultValues = [
        self::CONNECT_TIMEOUT => 2.0,
        self::TIMEOUT => 5.0,

        self::LOG_RESPONSE_BODY_SIZE  => 1024,
        self::LOG_RESPONSE_BODY_REGEX => [],
    ];

    public function __construct()
    {
        $this->values = self::$defaultValues;
    }

    public function filterResponseBody(&$body, $bodySize, $configValues)
    {
        $desiredBodySize = $configValues[self::LOG_RESPONSE_BODY_SIZE];
        $this->filterSize($body, $bodySize, $desiredBodySize);

        if ($desiredBodySize === 0) {
            return;
        }

        $this->filterRegex($body, $configValues[self::LOG_RESPONSE_BODY_REGEX]);
    }
}