<?php
/**
 * Created by PhpStorm.
 * User: Frost Wong <frostwong@gmail.com>
 * Date: 20/06/2017
 * Time: 17:06
 */

namespace App\Command;


use PocFramework\Support\CLI\CliBase;
use PocFramework\Support\Log;

class Test extends CliBase
{

    public function run()
    {
        Log::info('i am running');

        echo 'hello';
        throw new \Exception('ksdfjsakd');

        Log::info('I executed finished');
    }
}