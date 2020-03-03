<?php
/**
 * Created by PhpStorm.
 * User: Frost Wong <frostwong@gmail.com>
 * Date: 21/06/2017
 * Time: 14:41
 */

namespace PocFramework\Support\CLI;


use PocFramework\IoC;
use PocFramework\Support\Log;
use PocFramework\Utils\Timer;
use Psr\Container\ContainerInterface;

class App extends IoC
{
    /**
     * @var CommandLineInterface
     */
    private $instance;

    private $instanceClass;

    public function __construct(ContainerInterface $container, $cls)
    {
        parent::__construct($container);

        Timer::start('console');
        $this->instanceClass = $cls;
        $this->instance = new $cls($container);
    }

    public function run()
    {
        Log::info('Starting run task ' . $this->instanceClass);

        try {
            $this->instance->run();

        } catch (\Exception $e) {
            $cost = Timer::stop('console');
            Log::info('Run task ' . $this->instanceClass . ' failed, costs ' . $cost . 'ms');
            call_user_func_array($this->ci['cliErrorHandler'], [$e]);
            throw $e;
        }

        $cost = Timer::stop('console');
        Log::info('Completed task ' . $this->instanceClass . ' within ' . $cost . 'ms');
    }
}