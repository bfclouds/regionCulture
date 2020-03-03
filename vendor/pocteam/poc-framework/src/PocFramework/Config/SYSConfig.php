<?php
/**
 * User: coderd
 * Date: 2019/4/9
 * Time: 17:34
 */

namespace PocFramework\Config;


use PocFramework\Support\Authorization\AppKeyAuthInterface;

class SYSConfig
{
    /**
     * @var Log
     */
    private $log;

    /**
     * @var AppKeyAuthInterface
     */
    private $appKeyAuth;

    /**
     * @var RPC
     */
    private $RPC;

    private static $i;

    /**
     * @return SYSConfig
     */
    public static function instance()
    {
        if (self::$i === null) {
            self::$i = new self();
        }

        return self::$i;
    }

    private function __construct()
    {
        $this->log = new Log();
        $this->RPC = new RPC();
    }

    /**
     * @param Log $log
     */
    public function withLog(Log $log)
    {
        $this->log = $log;
    }

    /**
     * @return Log
     */
    public function Log()
    {
        return $this->log;
    }

    /**
     * @param AppKeyAuthInterface $appKeyAuth
     */
    public function withAppKeyAuth(AppKeyAuthInterface $appKeyAuth)
    {
        $this->appKeyAuth = $appKeyAuth;
    }

    /**
     * @return AppKeyAuthInterface
     */
    public function appKeyAuth()
    {
        return $this->appKeyAuth;
    }

    public function RPC()
    {
        return $this->RPC;
    }

    /**
     * Not allow clone
     */
    private function __clone()
    {
    }
}