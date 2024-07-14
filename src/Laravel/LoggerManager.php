<?php

namespace YukataRm\Laravel\SimpleLogger;

use YukataRm\Laravel\SimpleLogger\Interface\ManagerInterface;
use YukataRm\SimpleLogger\Proxy\LoggerManager as SimpleLoggerManager;

use YukataRm\Laravel\SimpleLogger\Logger;
use YukataRm\SimpleLogger\Enum\LogLevelEnum;

/**
 * Facadeを経由してstaticにアクセスされるManager
 * 
 * @package YukataRm\Laravel\SimpleLogger
 */
class LoggerManager extends SimpleLoggerManager implements ManagerInterface
{
    /**
     * Loggerのインスタンスを生成する
     *
     * @param \YukataRm\SimpleLogger\Enum\LogLevelEnum $logLevel
     * @return \SimpleLogger\Laravel\Logger
     */
    public function make(LogLevelEnum $logLevel): Logger
    {
        return new Logger($logLevel);
    }
}
