<?php

namespace YukataRm\SimpleLogger\Interface;

use YukataRm\SimpleLogger\Enum\LogLevelEnum;
use YukataRm\SimpleLogger\Enum\LogFormatEnum;

/**
 * LoggerのInterface
 * 
 * @package YukataRm\SimpleLogger\Interface
 */
interface LoggerInterface
{
    /*----------------------------------------*
     * Constructor
     *----------------------------------------*/

    /**
     * ログの出力レベルを取得する
     *
     * @return \YukataRm\SimpleLogger\Enum\LogLevelEnum
     */
    public function logLevel(): LogLevelEnum;

    /*----------------------------------------*
     * Logging
     *----------------------------------------*/

    /**
     * ログを出力する
     *
     * @return void
     */
    public function logging(): void;

    /*----------------------------------------*
     * Messages
     *----------------------------------------*/

    /**
     * メッセージを追加する
     *
     * @param mixed $message
     * @param mixed $value
     * @return static
     */
    public function add(mixed $message, mixed $value = null, bool $isEmphasis = false): static;

    /**
     * メッセージを強調して追加する
     *
     * @param mixed $message
     * @return static
     */
    public function addEmphasis(mixed $message): static;

    /**
     * ログに共通部分だけの空の行を追加する
     *
     * @return static
     */
    public function addEmpty(): static;

    /**
     * ログに共通部分だけの区切り線を追加する
     *
     * @return static
     */
    public function addDivider(): static;

    /*----------------------------------------*
     * Format
     *----------------------------------------*/

    /**
     * ログを出力する際のフォーマットを設定する
     *
     * @param \YukataRm\SimpleLogger\Enum\LogFormatEnum|string $format
     * @return static
     */
    public function setFormat(LogFormatEnum|string $format): static;

    /**
     * ログを出力する際のフォーマットを追加する
     *
     * @param \YukataRm\SimpleLogger\Enum\LogFormatEnum|string $format
     * @return static
     */
    public function addFormat(LogFormatEnum|string $format): static;

    /*----------------------------------------*
     * Directory
     *----------------------------------------*/

    /**
     * ログファイルの出力先ディレクトリを設定する
     *
     * @param string $directory
     * @return static
     */
    public function setDirectory(string $directory): static;

    /**
     * ログファイルの出力先ディレクトリを追加する
     *
     * @param string $directory
     * @return static
     */
    public function addDirectory(string $directory): static;

    /*----------------------------------------*
     * File Name
     *----------------------------------------*/

    /**
     * ログファイルのファイル名を設定する
     *
     * @param string $fileName
     * @return static
     */
    public function setFileName(string $fileName): static;

    /**
     * ログファイルのファイル名のフォーマットを設定する
     *
     * @param string $fileNameFormat
     * @return static
     */
    public function setFileNameFormat(string $fileNameFormat): static;

    /**
     * ログファイルの拡張子を設定する
     *
     * @param string $fileExtension
     * @return static
     */
    public function setFileExtension(string $fileExtension): static;

    /*----------------------------------------*
     * StackTrace
     *----------------------------------------*/

    /**
     * スタックトレースを取得する為のindexを設定する
     * 
     * @param int $index
     * @return static
     */
    public function setStackTraceIndex(int $index): static;
}
