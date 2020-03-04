<?php
/**
 * User: coderd
 * Date: 2019/4/15
 * Time: 16:48
 */

namespace PocFramework\Config;


use PocFramework\Utils\Arr;

class ConfigBase
{
    /**
     * @var array
     */
    protected $values = [];

    public function withValue($key, $value)
    {
        $this->values[$key] = $value;
    }

    public function withoutValue($key)
    {
        if (isset($this->values[$key])) {
            unset($this->values[$key]);
        }
    }

    public function value($key)
    {
        return $this->values[$key];
    }

    public function withValues(array $values)
    {
        $this->values = $values;
    }

    public function values(array $keys = [])
    {
        if (empty($keys)) {
            return $this->values;
        }

        return Arr::getFields($this->values, $keys);
    }
}