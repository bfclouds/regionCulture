<?php
/**
 * User: coderd
 * Date: 2018/8/6
 * Time: 17:06
 */

namespace App\Lib\Database;


use App\Lib\Support\Str;
use PocFramework\Support\Log;

class Redis
{
    /**
     * The valid
     * @var array
     */
    private $validQueryTypeList = ['regional', 'master'];

    /**
     * The type of query, must be 'master' or 'slave'
     * @var string
     */
    private $queryType;

    /**
     * Redis object
     * @var \Redis
     */
    private $link;

    /**
     * Redis object pool
     * @var array
     */
    private $linkPool = [];

    /**
     * The configuration options for redis
     *
     * @var array
     */
    private $config;

    /**
     * @var bool If or not throw exceptions once
     */
    private $throwExceptionOnce = false;

    /**
     * The redis configuration
     *
     * @param $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @return $this
     */
    public function throwException()
    {
        $this->throwExceptionOnce = true;

        return $this;
    }

    private function withQueryType($queryType)
    {
        if (!in_array($queryType, $this->validQueryTypeList)) {
            throw new \InvalidArgumentException('Redis: invalid query type(' . $queryType . ')');
        }
        $this->queryType = $queryType;
    }

    private function config($queryType)
    {
        if (empty($this->config[$queryType])) {
            throw new \InvalidArgumentException('Redis: query type(' . $queryType . ') not configured');
        }

        return $this->config[$queryType];
    }

    private function configExists($queryType)
    {
        return isset($this->config[$queryType]);
    }

    private function link($queryType = 'regional')
    {
        $this->withQueryType($queryType);
        if (
            !is_object($this->linkPool[$this->queryType])
            && !$this->connect()
        ) {
            return false;
        }
        $this->link = $this->linkPool[$this->queryType];

        return true;
    }

    /**
     * Connect redis server.
     *
     * @return bool
     * @throws \Exception
     */
    private function connect()
    {
        $config = $this->config($this->queryType);
        $link = new \Redis();
        try {
            Log::debug("Redis: connecting redis({$config['host']}:{$config['port']}), timeout set be {$config['timeout']}s");

            $result = $link->connect($config['host'], $config['port'], $config['timeout']);

            Log::debug('Redis: connect redis result: ' . $result);

            if ($config['password']) {
                $link->auth($config['password']);
            }
            if (isset($config['database'])) {
                $link->select($config['database']);
            }

            $this->linkPool[$this->queryType] = $link;
        } catch (\Exception $e) {
            trigger_error(
                "Errors on connecting redis: {$config['host']}:{$config['port']} with exception: " . Str::exceptionToString($e),
                E_USER_WARNING
            );
            Log::error("Redis: connecting redis({$config['host']}:{$config['port']}) failed, with exception: "
                . Str::exceptionToStringWithoutLF($e));

            if ($this->throwExceptionOnce) {
                $this->throwExceptionOnce = false;
                throw $e;
            }

            return false;
        }

        return true;
    }

    private static $slaveMethods = [
        'get' => '', 'exists' => '', 'getMultiple' => '', 'lSize' => '', 'lIndex' => '',
        'lGet' => '', 'lRange' => '', 'lGetRange' => '', 'sIsMember' => '', 'sContains' => '', 'sCard' => '',
        'sSize' => '', 'sRandMember' => '', 'sInter' => '', 'sInterStore' => '', 'sUnion' => '', 'sUnionStore' => '',
        'sDiff' => '', 'sDiffStore' => '', 'sMembers' => '', 'sGetMembers' => '', 'randomKey' => '', 'keys' => '',
        'getKeys' => '', 'dbSize' => '', 'type' => '', 'getRange' => '', 'strlen' => '', 'getBit' => '', 'info' => '',
        'ttl' => '', 'zRange' => '', 'zRevRange' => '', 'zRangeByScore' => '', 'zRevRangeByScore' => '', 'zCount' => '',
        'zSize' => '', 'zCard' => '', 'zScore' => '', 'zRank' => '', 'zRevRank' => '', 'zUnion' => '', 'zInter' => '',
        'hGet' => '', 'hLen' => '', 'hKeys' => '', 'hVals' => '', 'hGetAll' => '', 'hExists' => '', 'hMGet' => ''
    ];

    /**
     * Dynamically pass methods to the redis
     *
     * @param  string $method
     * @param  array $parameters
     * @return mixed
     * @throws \Exception
     */
    public function __call($method, $parameters)
    {
        if (array_key_exists($method, self::$slaveMethods) && $this->configExists('master')) {
            $queryType = 'master';
        } else {
            $queryType = 'regional';
        }

        Log::debug('Redis: query type: ' . $queryType . ', method: ' . $method);

        if (!$this->link($queryType)) {
            return false;
        }

        try {
            $result = call_user_func_array([$this->link, $method], $parameters);
            Log::debug('Redis: ' . $method . ' result: ' . $result);
        } catch (\Exception $e) {
            trigger_error(
                'Errors on operating redis: ' . Str::exceptionToString($e),
                E_USER_WARNING
            );
            Log::error("Redis: errors on operating redis, with exception: " . Str::exceptionToStringWithoutLF($e));

            if ($this->throwExceptionOnce) {
                $this->throwExceptionOnce = false;
                throw $e;
            }

            return false;
        }

        $this->throwExceptionOnce = false;

        return $result;
    }

    public function mySet($key, $value, $timeout)
    {
        return $this->set($key, json_encode($value), $timeout);
    }

    public function myGet($key)
    {
        return json_decode($this->get($key), true);
    }

    public function myDelete($key)
    {
        return $this->delete($key);
    }

    /**
     * Close the redis connection
     */
    private function close()
    {
        try {
            if (is_array($this->linkPool)) {
                foreach ($this->linkPool as $link) {
                    $link->close();
                }
            }
        } catch (\Exception $e) {
            trigger_error(
                'Errors on operating redis: ' . Str::exceptionToString($e),
                E_USER_WARNING
            );
        }

        $this->linkPool = [];
    }

    public function __destruct()
    {
        $this->close();
    }
}