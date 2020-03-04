<?php
/**
 * Created by PhpStorm.
 * User: Frost Wong <frostwong@gmail.com>
 * Date: 15/05/2017
 * Time: 22:52
 */

namespace PocFramework\Support;


class Session
{
    private $data = [];
    private $ak;
    private $sk;

    final public function __construct()
    {
    }

    public function init(array $data)
    {
        $this->data = $data;
        Log::info('Init session info', $data);
        return true;
    }

    public function get()
    {
        return $this->data;
    }

    public function setAkSk($ak, $sk)
    {
        $this->ak = $ak;
        $this->sk = $sk;
    }

    public function getAk()
    {
        return $this->ak;
    }

    public function getSk()
    {
        return $this->sk;
    }
}