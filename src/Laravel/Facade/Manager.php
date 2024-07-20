<?php

namespace YukataRm\Laravel\SimpleLogger\Facade;

use YukataRm\SimpleLogger\Proxy\LoggerManager as SimpleLoggerManager;

use YukataRm\Laravel\SimpleLogger\Interface\LoggerInterface;
use YukataRm\Laravel\SimpleLogger\Logger;
use YukataRm\SimpleLogger\Enum\LogLevelEnum;

/**
 * Facadeを経由してstaticにアクセスされるManager
 * 
 * @package YukataRm\Laravel\SimpleLogger\Facade
 */
class Manager extends SimpleLoggerManager
{
    /**
     * Loggerのインスタンスを生成する
     *
     * @param \YukataRm\SimpleLogger\Enum\LogLevelEnum $logLevel
     * @return \SimpleLogger\Laravel\Interface\LoggerInterface
     */
    public function make(LogLevelEnum $logLevel): LoggerInterface
    {
        return new Logger($logLevel);
    }
}
