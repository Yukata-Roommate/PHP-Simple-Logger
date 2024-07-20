<?php

namespace YukataRm\Laravel\SimpleLogger\Facade;

use YukataRm\SimpleLogger\Proxy\Manager as SimpleLoggerManager;

use YukataRm\Laravel\SimpleLogger\Interface\LoggerInterface;
use YukataRm\Laravel\SimpleLogger\Logger;
use YukataRm\SimpleLogger\Enum\LogLevelEnum;

/**
 * Facade Manager
 * 
 * @package YukataRm\Laravel\SimpleLogger\Facade
 */
class Manager extends SimpleLoggerManager
{
    /**
     * Loggerのインスタンスを生成する
     *
     * @param \YukataRm\SimpleLogger\Enum\LogLevelEnum $logLevel
     * @return \YukataRm\Laravel\SimpleLogger\Interface\LoggerInterface
     */
    public function make(LogLevelEnum $logLevel): LoggerInterface
    {
        return new Logger($logLevel);
    }
}
