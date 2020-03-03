<?php
/**
 * Created by PhpStorm.
 * User: Frost Wong <frostwong@gmail.com>
 * Date: 2017/4/25
 * Time: 22:21
 */

namespace PocFramework\Mvc;

use PocFramework\Utils\AcceptLanguage;
use PocFramework\Utils\XmlEncoder;
use Slim\Http\Response;

class AbstractOpenApiView
{
    const OK = 10000;
    const UNDEFINED_ERROR = -10000;

    protected $undefinedMessage = [500, 'undefined error'];
    protected $okMessage = [200, 'OK'];
    protected $xmlns = '""';
    protected $template = '<ErrorResponse xmlns=%s><Error><Type>%s</Type><Code>%s</Code><Message><![CDATA[%s]]></Message></Error><RequestId>%s</RequestId></ErrorResponse>';

    protected $type = 'Type';
    protected $code = 'Code';
    protected $message = 'Message';
    protected $requestId = 'RequestId';

    /**
     * @var Response
     */
    private $response;

    /**
     * @var array
     */
    protected $codeMsgs = [];

    /**
     * Action name used to render response
     *
     * @var string
     */
    private $action;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    /**
     * Append specific header to response
     *
     * @param $name
     * @param $value
     * @return $this
     */
    public function withHeader($name, $value)
    {
        $this->response = $this->response->withHeader($name, $value);

        return $this;
    }

    /**
     * Pass query string Action here
     *
     * @param $action
     * @return $this
     */
    public function withAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Make response.
     *
     * @param $data
     * @param int $code
     * @param string $type
     * @param array $params
     * @return Response
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    final public function respond($data, $code = self::OK, $type = '', ...$params)
    {
        list($httpCode, $message) = $this->get($code);
        $message = $this->handleMultiLanguages($message);
        array_unshift($params, $message);
        $message = call_user_func_array('sprintf', $params);

        if ($this->isJsonResponse()) {
            if ($httpCode > 200) {
                $json = $this->composeErrorJsonResponse($type, $code, $message);
            } else {
                $json = $this->composeCorrectJsonResponse($data);
            }

            return $this->response->withHeader('Content-Type', 'application/json;charset=UTF-8')
                ->withStatus($httpCode)
                ->withJson($json);
        }

        if ($httpCode > 200) {
            $xml = $this->composeErrorXmlResponse($type, $code, $message);
        } else {
            $xml = $this->composeCorrectXmlResponse($data);
        }

        return $this->response->withHeader('Content-Type', 'application/xml;charset=UTF-8')
            ->withStatus($httpCode)
            ->write($xml);
    }


    /**
     * Determine the most outside node name
     *
     * @return string
     * @throws InvalidOpenApiActionException
     */
    private function determineParentNode()
    {
        if (!isset($this->action)) {
            throw new InvalidOpenApiActionException('Action must be set before respond');
        }

        if ((int)$this->response->getStatusCode() === 200) {
            return $this->action . 'Response';
        }

        return 'ErrorResponse';
    }

    /**
     * Determine which message to show with HTTP_ACCEPT_LANGUAGE
     *
     * @param $message
     * @return mixed
     */
    private function handleMultiLanguages($message)
    {
        if (is_string($message)) {
            return $message;
        }

        $al = AcceptLanguage::get();
        if (is_array($message) && isset($message[$al])) {
            return $message[$al];
        }
    }

    protected function get(&$code)
    {
        if ($code === self::OK) {
            return $this->okMessage;
        }

        if (isset($this->codeMsgs[$code])) {
            return $this->codeMsgs[$code];
        }

        return $this->undefinedMessage;
    }

    /**
     * Return xml response as default
     *
     * @return string
     */
    private function determineResponseContentType()
    {
        if (!isset($_SERVER['HTTP_ACCEPT']) || !in_array(strtolower($_SERVER['HTTP_ACCEPT']), ['application/json', 'application/xml'])) {
            return 'application/xml';
        }

        return $_SERVER['HTTP_ACCEPT'];
    }


    private function isJsonResponse()
    {
        return $this->determineResponseContentType() === 'application/json';
    }

    private function composeCorrectJsonResponse($data)
    {
        $data['RequestId'] = REQUEST_ID;

        return $data;
    }

    private function composeCorrectXmlResponse($data)
    {
        $node = $this->determineParentNode();
        $data = XmlEncoder::encode($data);

        $xml = '<' . $node;
        $xml .= '>';
        $xml .= $data;
        $xml .= '<ResponseMetadata><RequestId>' . REQUEST_ID . '</RequestId></ResponseMetadata>';
        $xml .= '</' . $node . '>';

        return $xml;
    }


    private function composeErrorJsonResponse($type, $code, $message)
    {
        $json[$this->requestId] = REQUEST_ID;
        $json['Error'] = [
            $this->type => $type,
            $this->code => $code,
            $this->message => $message,
        ];

        return $json;
    }

    private function composeErrorXmlResponse($type, $code, $message)
    {
        $xml = sprintf($this->template, $this->xmlns, $type, $code, $message, REQUEST_ID);

        return $xml;
    }
}