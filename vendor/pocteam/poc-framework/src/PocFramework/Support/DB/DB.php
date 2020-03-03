<?php
/**
 * Created by PhpStorm.
 * User: Frost Wong <frostwong@gmail.com>
 * Date: 03/05/2017
 * Time: 18:16
 */

namespace PocFramework\Support\DB;


use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Database\Events\StatementPrepared;
use Illuminate\Events\Dispatcher;
use PDO;
use PocFramework\ConfigInterface;
use PocFramework\Support\Config;
use PocFramework\Support\Log;

class DB
{
    /**
     * 使用轻量级的Query Builder而不是Eloquent
     *
     * @param $param
     * @return \Illuminate\Database\Connection
     * @throws InvalidDatabaseNameException
     */
    public static function makePdo($param)
    {
        if (\is_array($param)) {
            if (empty($param)) {
                Log::critical('Database configuration is empty');
                throw new InvalidDatabaseNameException('No configuration is set for database');
            }

            return self::doMakeConnection($param);
        }

        $configs = (new Config('database'))->toArray();
        if (!isset($configs[$param])) {
            Log::critical('No configuration is set for database ' . $param);
            throw new InvalidDatabaseNameException('No configuration is set for database ' . $param);
        }

        return self::doMakeConnection($configs[$param]);
    }

    private static function doMakeConnection(array $config)
    {
        $capsule = new Manager();
        $capsule->addConnection($config);
        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        $connection = $capsule->getConnection();
        $connection->enableQueryLog();

        $dispatcher = new Dispatcher();
        $dispatcher->listen(StatementPrepared::class, function ($event) {
            $event->statement->setFetchMode(PDO::FETCH_ASSOC);
        });

        $dbName = $config['database'];
        $dispatcher->listen(QueryExecuted::class, function ($event) use($dbName) {
            $sql = str_replace("?", "'%s'", $event->sql);
            $query = vsprintf($sql, $event->bindings);
            $line = '[MYSQL] db_name:' . $dbName . '|sql:' . $query . '|cost:' . $event->time;
            if ($event->time < 1000) {
                Log::info($line);
            } else if ($event->time < 2000) {
                Log::warning($line);
            } else {
                // Slow Query
                Log::error($line);
            }
        });
        $connection->setEventDispatcher($dispatcher);

        return $connection;
    }
}