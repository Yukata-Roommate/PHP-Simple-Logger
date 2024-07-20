<?php

namespace YukataRm\SimpleLogger\Proxy;

use YukataRm\SimpleLogger\Interface\LoggerInterface;
use YukataRm\SimpleLogger\Logger;
use YukataRm\SimpleLogger\Enum\LogLevelEnum;

/**
 * Proxyを経由してstaticにアクセスされるManager
 * 
 * @package YukataRm\SimpleLogger\Proxy
 */
class Manager
{
    /**
     * Loggerのインスタンスを生成する
     *
     * @param \YukataRm\SimpleLogger\Enum\LogLevelEnum $logLevel
     * @return \YukataRm\SimpleLogger\Interface\LoggerInterface
     */
    public function make(LogLevelEnum $logLevel): LoggerInterface
    {
        return new Logger($logLevel);
    }

    /*----------------------------------------*
     * Create Instance
     *----------------------------------------*/

    /**
     * DebugレベルのLoggerのインスタンスを生成する
     *
     * @return \YukataRm\SimpleLogger\Interface\LoggerInterface
     */
    public function debug(): LoggerInterface
    {
        return $this->make(LogLevelEnum::DEBUG);
    }

    /**
     * InfoレベルのLoggerのインスタンスを生成する
     *
     * @return \YukataRm\SimpleLogger\Interface\LoggerInterface
     */
    public function info(): LoggerInterface
    {
        return $this->make(LogLevelEnum::INFO);
    }

    /**
     * NoticeレベルのLoggerのインスタンスを生成する
     *
     * @return \YukataRm\SimpleLogger\Interface\LoggerInterface
     */
    public function notice(): LoggerInterface
    {
        return $this->make(LogLevelEnum::NOTICE);
    }

    /**
     * WarningレベルのLoggerのインスタンスを生成する
     *
     * @return \YukataRm\SimpleLogger\Interface\LoggerInterface
     */
    public function warning(): LoggerInterface
    {
        return $this->make(LogLevelEnum::WARNING);
    }

    /**
     * ErrorレベルのLoggerのインスタンスを生成する
     *
     * @return \YukataRm\SimpleLogger\Interface\LoggerInterface
     */
    public function error(): LoggerInterface
    {
        return $this->make(LogLevelEnum::ERROR);
    }

    /**
     * CriticalレベルのLoggerのインスタンスを生成する
     *
     * @return \YukataRm\SimpleLogger\Interface\LoggerInterface
     */
    public function critical(): LoggerInterface
    {
        return $this->make(LogLevelEnum::CRITICAL);
    }

    /**
     * AlertレベルのLoggerのインスタンスを生成する
     *
     * @return \YukataRm\SimpleLogger\Interface\LoggerInterface
     */
    public function alert(): LoggerInterface
    {
        return $this->make(LogLevelEnum::ALERT);
    }

    /**
     * EmergencyレベルのLoggerのインスタンスを生成する
     *
     * @return \YukataRm\SimpleLogger\Interface\LoggerInterface
     */
    public function emergency(): LoggerInterface
    {
        return $this->make(LogLevelEnum::EMERGENCY);
    }

    /*----------------------------------------*
     * Logging
     *----------------------------------------*/

    /**
     * ログを出力する
     * 
     * @param \YukataRm\SimpleLogger\Interface\LoggerInterface $logger
     * @param mixed $message
     * @param mixed $value
     * @return void
     */
    protected function logging(LoggerInterface $logger, mixed $message, mixed $value = null): void
    {
        $logger->setStackTraceIndex(3)->add($message, $value)->logging();
    }

    /**
     * Debugレベルのログを出力する
     *
     * @param mixed $message
     * @param mixed $value
     * @return void
     */
    public function debugLog(mixed $message, mixed $value = null): void
    {
        $this->logging($this->debug(), $message, $value);
    }

    /**
     * Infoレベルのログを出力する
     *
     * @param mixed $message
     * @param mixed $value
     * @return void
     */
    public function infoLog(mixed $message, mixed $value = null): void
    {
        $this->logging($this->info(), $message, $value);
    }

    /**
     * Noticeレベルのログを出力する
     *
     * @param mixed $message
     * @param mixed $value
     * @return void
     */
    public function noticeLog(mixed $message, mixed $value = null): void
    {
        $this->logging($this->notice(), $message, $value);
    }

    /**
     * Warningレベルのログを出力する
     *
     * @param mixed $message
     * @param mixed $value
     * @return void
     */
    public function warningLog(mixed $message, mixed $value = null): void
    {
        $this->logging($this->warning(), $message, $value);
    }

    /**
     * Errorレベルのログを出力する
     *
     * @param mixed $message
     * @param mixed $value
     * @return void
     */
    public function errorLog(mixed $message, mixed $value = null): void
    {
        $this->logging($this->error(), $message, $value);
    }

    /**
     * Criticalレベルのログを出力する
     *
     * @param mixed $message
     * @param mixed $value
     * @return void
     */
    public function criticalLog(mixed $message, mixed $value = null): void
    {
        $this->logging($this->critical(), $message, $value);
    }

    /**
     * Alertレベルのログを出力する
     *
     * @param mixed $message
     * @param mixed $value
     * @return void
     */
    public function alertLog(mixed $message, mixed $value = null): void
    {
        $this->logging($this->alert(), $message, $value);
    }

    /**
     * Emergencyレベルのログを出力する
     *
     * @param mixed $message
     * @param mixed $value
     * @return void
     */
    public function emergencyLog(mixed $message, mixed $value = null): void
    {
        $this->logging($this->emergency(), $message, $value);
    }
}
