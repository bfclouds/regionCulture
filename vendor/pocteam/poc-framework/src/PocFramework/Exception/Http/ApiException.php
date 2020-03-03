<?php
/**
 * User: coderd
 * Date: 2018/10/11
 * Time: 19:56
 */

namespace PocFramework\Exception\Http;


class ApiException extends \Exception
{
    private $data = [];
    private $params = [];

    public function __construct($data = [], $code, ...$params)
    {
        $this->data = $data;
        $this->params = $params;

        parent::__construct('Api Exception', $code);
    }

    public function getData()
    {
        return $this->data;
    }

    public function getParams()
    {
        return $this->params;
    }
}