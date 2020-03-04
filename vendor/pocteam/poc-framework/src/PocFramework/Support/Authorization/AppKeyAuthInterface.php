<?php
/**
 * User: coderd
 * Date: 2018/8/20
 * Time: 20:52
 */

namespace PocFramework\Support\Authorization;


use Psr\Http\Message\ServerRequestInterface;

interface AppKeyAuthInterface
{
    public function check(ServerRequestInterface $request);

    public function appKey();
}