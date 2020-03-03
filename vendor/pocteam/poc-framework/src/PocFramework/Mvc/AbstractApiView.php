<?php
/**
 * Created by PhpStorm.
 * User: Frost Wong <frostwong@gmail.com>
 * Date: 2017/4/25
 * Time: 22:21
 */

namespace PocFramework\Mvc;

use PocFramework\Utils\AcceptLanguage;
use Slim\Http\Response;

class AbstractApiView
{
    const OK = 10000;
    const UNDEFINED_ERROR = -10000;

    protected $undefinedMessage = [500, 'undefined error'];
    protected $okMessage = [200, 'OK'];

    protected $code = 'errno';
    protected $data = 'data';
    protected $message = 'errmsg';
    protected $requestId = 'request_id';

    /**
     * @var Response
     */
    private $response;

    /**
     * @var array
     */
    protected $codeMsgs = [];

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    /**
     * Append specific header to response
     *
     * @param $name
     * @param $value
     * @return AbstractApiView
     * @throws \InvalidArgumentException
     */
    public function withHeader($name, $value)
    {
        $clone = clone $this;
        $clone->response = $clone->response->withHeader($name, $value);

        return $clone;
    }


    /**
     * Set status code before respond
     *
     * @param $code
     * @param string $reasonPhrase
     * @return AbstractApiView
     * @throws \InvalidArgumentException
     */
    public function withStatus($code, $reasonPhrase = '')
    {
        $clone = clone $this;
        $clone->response = $clone->response->withStatus($code, $reasonPhrase);

        return $clone;
    }

    /**
     * Make response.
     *
     * @param $data
     * @param int $code
     * @param array $params
     * @return Response
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    final public function respond($data, $code = self::OK, ...$params)
    {
        list($httpCode, $message) = $this->get($code);
        $message = $this->handleMultiLanguages($message);
        $array = [];
        $array[$this->code] = $code;
        array_unshift($params, $message);
        $array[$this->message] = sprintf(...$params);
        $array[$this->data] = $data;

        return $this->render($array, $httpCode);
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

    /**
     * @param array $data
     * @param int $httpCode
     * @return Response
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    private function render(array $data, $httpCode = 200)
    {
        $data[$this->requestId] = REQUEST_ID;
        $clone = clone $this;

        $response = $clone->response;
        if ((int)$response->getStatusCode() === 200) {
            $response = $response->withStatus($httpCode);
        }

        $contentType = $this->determineContentType(strtolower($_SERVER['HTTP_ACCEPT']));
        switch ($contentType) {
        case 'application/javascript':
            $func = isset($_GET['callback']) ? $_GET['callback'] : 'callback';
            return $response->withHeader('Content-Type', 'application/javascript;charset=UTF-8')->write($func . '(' . json_encode($data) . ')');
        case 'text/html':
            return $response->withHeader('Content-Type', 'text/html;charset=UTF-8')->write($this->renderHtml($data));
        default:
            return $response->withHeader('Content-Type', 'application/json;charset=UTF-8')->withJson($data);
        }
    }

    private function renderHtml($data)
    {
        $code = $data[$this->code];
        $message = $data[$this->message];
        $requestId = $data[$this->requestId];
        
        $output = sprintf(
            "<html><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8'>" .
            '<title>%s</title><style>body{margin:0;padding:30px;font:12px/1.5 Helvetica,Arial,Verdana,' .
            'sans-serif;}h1{margin:0;font-size:48px;font-weight:normal;line-height:48px;}strong{' .
            'display:inline-block;width:65px;}</style></head><body><h1>%s</h1>%s</body></html>',
            $code,
            $message,
            $requestId
        );
        return $output;
    }

    private function determineContentType($line)
    {
        $selectedContentTypes = explode(',', $line);

        if (count($selectedContentTypes)) {
            return current($selectedContentTypes);
        }

        return 'application/json';
    }

    private function get($code)
    {
        if ($code === self::OK) {
            return $this->okMessage;
        }

        if (isset($this->codeMsgs[$code])) {
            return $this->codeMsgs[$code];
        }

        return $this->undefinedMessage;
    }
}
