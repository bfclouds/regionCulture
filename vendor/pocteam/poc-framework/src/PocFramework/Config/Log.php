<?php
/**
 * User: coderd
 * Date: 2019/4/11
 * Time: 16:14
 */

namespace PocFramework\Config;


class Log extends ConfigBase
{
    use FilterTrait;

    const HTTP_REQUEST_BODY_SIZE   = 'http.request.body.size';
    const HTTP_REQUEST_BODY_REGEX  = 'http.request.body.regex';
    const HTTP_RESPONSE_BODY_SIZE  = 'http.response.body.size';
    const HTTP_RESPONSE_BODY_REGEX = 'http.response.body.regex';

    private static $defaultValues = [
        self::HTTP_REQUEST_BODY_SIZE   => -1,
        self::HTTP_REQUEST_BODY_REGEX  => [],
        self::HTTP_RESPONSE_BODY_SIZE  => 1024,
        self::HTTP_RESPONSE_BODY_REGEX => [],
    ];

    public function __construct()
    {
        $this->values = self::$defaultValues;
    }

    public function filterRequestBody(&$body, $bodySize)
    {
        $desiredBodySize = $this->values[self::HTTP_REQUEST_BODY_SIZE];
        $this->filterSize($body, $bodySize, $desiredBodySize);

        if ($desiredBodySize === 0) {
            return;
        }

        $this->filterRegex($body, $this->values[self::HTTP_REQUEST_BODY_REGEX]);
    }

    public function filterResponseBody(&$body, $bodySize)
    {
        $desiredBodySize = $this->values[self::HTTP_RESPONSE_BODY_SIZE];
        $this->filterSize($body, $bodySize, $desiredBodySize);

        if ($desiredBodySize === 0) {
            return;
        }

        $this->filterRegex($body, $this->values[self::HTTP_RESPONSE_BODY_REGEX]);
    }
}