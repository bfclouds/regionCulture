<?php
/**
 * Created by PhpStorm.
 * User: Frost Wong <frostwong@gmail.com>
 * Date: 08/06/2017
 * Time: 17:59
 */

namespace PocFramework;


/**
 * Default implementation of ConfigInterface
 *
 * @package PocFramework
 */
class ConfigDefault implements ConfigInterface
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function get()
    {
        return $this->data;
    }
}