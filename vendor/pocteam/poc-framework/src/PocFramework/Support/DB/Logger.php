<?php
/**
 * Created by PhpStorm.
 * User: Frost Wong <frostwong@gmail.com>
 * Date: 08/05/2017
 * Time: 16:38
 */

namespace PocFramework\Support\DB;


use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger as MonologLogger;
use Psr\Log\LoggerInterface;

class Logger
{
    /**
     * @var LoggerInterface
     */
    private static $sqlLogger;

    public static function makeLogger()
    {
        if (null === self::$sqlLogger) {
            $handler = new RotatingFileHandler(LOG_DIR . '/sql.log');
            $logger = new MonologLogger('sql');
            $formatterWithRequestId = new LineFormatter(
                "[%datetime%] [" . REQUEST_ID . "] %channel%.%level_name%: %message% %context% %extra%\n",
                LineFormatter::SIMPLE_DATE,
                false,
                true
            );
            $handler->setFormatter($formatterWithRequestId);
            $logger->pushHandler($handler);
            self::$sqlLogger = $logger;
        }

        return self::$sqlLogger;
    }
}