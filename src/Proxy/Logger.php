<?php

namespace YukataRm\SimpleLogger\Proxy;

use YukataRm\StaticProxy\StaticProxy;

use YukataRm\SimpleLogger\Proxy\LoggerManager;

/**
 * LoggerのProxy
 * LoggerManagerのMethodをstaticに呼び出せるようにする
 * 
 * @package YukataRm\SimpleLogger\Proxy
 * 
 * @method static \YukataRm\SimpleLogger\Interface\LoggerInterface make(\YukataRm\SimpleLogger\Enum\LogLevelEnum $logLevel)
 * 
 * @method static \YukataRm\SimpleLogger\Interface\LoggerInterface debug()
 * @method static \YukataRm\SimpleLogger\Interface\LoggerInterface info()
 * @method static \YukataRm\SimpleLogger\Interface\LoggerInterface notice()
 * @method static \YukataRm\SimpleLogger\Interface\LoggerInterface warning()
 * @method static \YukataRm\SimpleLogger\Interface\LoggerInterface error()
 * @method static \YukataRm\SimpleLogger\Interface\LoggerInterface critical()
 * @method static \YukataRm\SimpleLogger\Interface\LoggerInterface alert()
 * @method static \YukataRm\SimpleLogger\Interface\LoggerInterface emergency()
 * 
 * @method static void debugLog(mixed $message, mixed $value = null)
 * @method static void infoLog(mixed $message, mixed $value = null)
 * @method static void noticeLog(mixed $message, mixed $value = null)
 * @method static void warningLog(mixed $message, mixed $value = null)
 * @method static void errorLog(mixed $message, mixed $value = null)
 * @method static void criticalLog(mixed $message, mixed $value = null)
 * @method static void alertLog(mixed $message, mixed $value = null)
 * @method static void emergencyLog(mixed $message, mixed $value = null)
 * 
 * @see \YukataRm\SimpleLogger\Proxy\Interface\ManagerInterface
 */
class Logger extends StaticProxy
{
    /** 
     * LoggerManagerの実クラス名を取得する
     * 
     * @return string 
     */
    public static function getClassName(): string
    {
        return LoggerManager::class;
    }
}
