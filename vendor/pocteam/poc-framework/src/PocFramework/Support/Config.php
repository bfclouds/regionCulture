<?php
/**
 * Created by PhpStorm.
 * User: Frost Wong <frostwong@gmail.com>
 * Date: 02/05/2017
 * Time: 22:11
 */

namespace PocFramework\Support;


use Zend\Config\Reader\Ini;
use Zend\Config\Reader\ReaderInterface;

class Config
{
    private $module;

    /**
     * @var ReaderInterface
     */
    private $reader;

    public function __construct($module = 'app')
    {
        $this->module = $module;
        $this->reader = new Ini();
    }

    /**
     * 返回配置文件数组
     * @return mixed
     */
    public function toArray()
    {
        $ini_conf_path = CONFIG_DIR . '/' . $this->module . '.ini';
        if(is_file($ini_conf_path)) {
            return $this->getIniConf();
        }
        return $this->getPhpConf();
    }


    /**
     * 读取php类型的配置文件
     * @return mixed
     */
    public function getPhpConf()
    {
        $conf_path = sprintf('%s/%s/%s.php', CONFIG_DIR, ENV, $this->module);
        return require($conf_path);
    }

    /**
     * 读取ini类型的配置文件
     * @return mixed
     */
    public function getIniConf()
    {
        $data = $this->reader->fromFile(CONFIG_DIR . '/' . $this->module . '.ini');
        return $data[ENV];
    }
}