<?php
/**
 * Created by PhpStorm.
 * User: Frost Wong <frostwong@gmail.com>
 * Date: 20/06/2017
 * Time: 16:53
 */

namespace PocFramework\Support\CLI;


use PocFramework\IoC;

abstract class CliBase extends IoC implements CommandLineInterface
{
    abstract public function run();
}