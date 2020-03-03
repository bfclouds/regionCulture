<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/27
 * Time: 1:08
 */

namespace PocFramework;

use PHPUnit_Framework_TestCase;
use PocFramework\Support\Config\IniConfig;

class IniConfigTest extends PHPUnit_Framework_TestCase
{
    public function testRawArray()
    {
        $iniConfig = new IniConfig(__DIR__ . '/../../application.ini');
        $array = $iniConfig->toArray('prod');
        $expected = array (
            'database.profile.host' => '127.0.0.1',
            'database.profile.port' => '3306',
            'database.profile.user' => 'root',
            'database.profile.pass' => 'root',
        );
        $this->assertEquals($expected, $array);
    }
}