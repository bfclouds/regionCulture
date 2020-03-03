<?php
/**
 * User: coderd
 * Date: 2018/8/20
 * Time: 20:56
 */

namespace PocFramework\Support\Authorization;


use Slim\Route;
use Psr\Http\Message\ServerRequestInterface;

class AppKeyBasicAuth implements AppKeyAuthInterface
{
    /**
     * @var string
     */
    private $appKey;

    /**
     * @var Route
     */
    private $route;

    private $appKeys = [];

    private $permissions = [];

    public function check(ServerRequestInterface $request)
    {
        $this->route = $request->getAttribute('route');
        $authorization = $request->getServerParams()['HTTP_AUTHORIZATION'];

        list($name, $value) = explode(' ', $authorization, 2);
        if ($name === 'Basic') {
            list($appKey, $secretKey) = explode(':', base64_decode($value));
            if ($this->checkAkSk($appKey, $secretKey) === false) {
                return false;
            }

            if ($this->checkPermission($appKey, $this->route->getName()) === true) {
                return true;
            }
        }

        return false;
    }

    private function checkAkSk($appKey, $secretKey)
    {
        return $appKey && $secretKey && $this->appKeys[$appKey] === $secretKey;
    }

    private function checkPermission($appKey, $apiName)
    {
        $appKeyPermission = $this->permissions[$appKey];
        if ($appKeyPermission === null) {
            return false;
        }
        if ('*' === $appKeyPermission) {
            return true;
        }

        if (is_array($appKeyPermission) && in_array($apiName, $appKeyPermission, true)) {
            return true;
        }

        return false;
    }

    /**
     * Get current request client app key
     *
     * @return string
     */
    public function appKey()
    {
        return $this->appKey;
    }

    /**
     * Set all app keys
     *
     * @param array $appKeys like this  [
     *                                      'app_key1' => 'secret_key1',
     *                                      'app_key2' => 'secret_key2'
     *                                  ]
     */
    public function withAppKeys(array $appKeys)
    {
        $this->appKeys = $appKeys;
    }

    /**
     * Set all app key permissions config
     *
     * @param array $permissions like this  [
     *                                          'app_key1' => '*',
     *                                          'app_key2' => ['api1', 'api2']
     *                                      ]
     *
     */
    public function withPermissions(array $permissions)
    {
        $this->permissions = $permissions;
    }
}