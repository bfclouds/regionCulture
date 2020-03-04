<?php
/**
 * Created by PhpStorm.
 * User: Frost Wong <frostwong@gmail.com>
 * Date: 2017/4/25
 * Time: 22:19
 */

namespace PocFramework;

use PocFramework\Support\Log;
use Psr\Container\ContainerInterface;

class IoC
{
    /**
     * @var ContainerInterface
     */
    protected $ci;

    /**
     * @var array
     */
    protected $instanceBag = [];

    public function __construct(ContainerInterface $container)
    {
        $this->ci = $container;
    }

    public function __get($id)
    {
        return $this->ci->get($id);
    }

    public function has($id)
    {
        return $this->ci->has($id);
    }

    public function get($className, ...$args)
    {
        $index = md5($className . json_encode($args));
        if (isset($this->instanceBag[$index]) && $this->instanceBag[$index] !== null) {
            return $this->instanceBag[$index];
        }

        if ($className === __CLASS__) {
            throw new \Exception('Class ' . __CLASS__ . ' can only be  instantiated once.');
        }

        if (is_subclass_of($className, __CLASS__)) {
            Log::debug('Instantiating IoC class with index ' . $index, ['className' => $className, 'args' => $args]);
            $this->instanceBag[$index] = new $className($this->ci, ...$args);
        } else {
            Log::debug('Instantiating class with index ' . $index, ['className' => $className, 'args' => $args]);
            $this->instanceBag[$index] = new $className(...$args);
        }

        return $this->instanceBag[$index];
    }
}