<?php

namespace YukataRm\Laravel\SimpleLogger\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * LoggerのFacade
 * LoggerManagerのMethodをstaticに呼び出せるようにする
 * 
 * @package YukataRm\Laravel\SimpleLogger\Facade
 * 
 * @method static \YukataRm\Laravel\SimpleLogger\Interface\LoggerInterface make(\YukataRm\SimpleLogger\Enum\LogLevelEnum $logLevel)
 * 
 * @method static \YukataRm\Laravel\SimpleLogger\Interface\LoggerInterface debug()
 * @method static \YukataRm\Laravel\SimpleLogger\Interface\LoggerInterface info()
 * @method static \YukataRm\Laravel\SimpleLogger\Interface\LoggerInterface notice()
 * @method static \YukataRm\Laravel\SimpleLogger\Interface\LoggerInterface warning()
 * @method static \YukataRm\Laravel\SimpleLogger\Interface\LoggerInterface error()
 * @method static \YukataRm\Laravel\SimpleLogger\Interface\LoggerInterface critical()
 * @method static \YukataRm\Laravel\SimpleLogger\Interface\LoggerInterface alert()
 * @method static \YukataRm\Laravel\SimpleLogger\Interface\LoggerInterface emergency()
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
 * @see \YukataRm\Laravel\SimpleLogger\Facade\Manager
 */
class Logger extends Facade
{
    /** 
     * LoggerManagerにアクセスするためのFacadeの名前を取得する
     * 
     * @return string 
     */
    protected static function getFacadeAccessor(): string
    {
        return static::class;
    }
}
