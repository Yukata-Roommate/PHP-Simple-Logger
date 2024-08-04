<?php

namespace YukataRm\SimpleLogger;

use YukataRm\SimpleLogger\Interface\LoggerInterface;

use YukataRm\SimpleLogger\EnvLoader;
use YukataRm\SimpleLogger\Enum\LogLevelEnum;
use YukataRm\SimpleLogger\Enum\LogFormatEnum;

/**
 * ログを出力する
 * 
 * @package YukataRm\SimpleLogger
 */
class Logger implements LoggerInterface
{
    /*----------------------------------------*
     * Constructor
     *----------------------------------------*/

    /**
     * .envに定義された項目の値を保持するクラス
     * 
     * @var \YukataRm\SimpleLogger\EnvLoader
     */
    protected EnvLoader $env;

    /**
     * ログの出力レベル
     *
     * @var \YukataRm\SimpleLogger\Enum\LogLevelEnum
     */
    protected LogLevelEnum $logLevel;

    /**
     * コンストラクタ
     *
     * @param \YukataRm\SimpleLogger\Enum\LogLevelEnum $logLevel
     */
    public function __construct(LogLevelEnum $logLevel)
    {
        // .envファイルの内容を保持するクラスのインスタンスを生成
        $this->env = new EnvLoader();

        // ログの出力レベルをセット
        $this->logLevel = $logLevel;
    }

    /**
     * ログの出力レベルを取得する
     *
     * @return LogLevelEnum
     */
    public function logLevel(): LogLevelEnum
    {
        return $this->logLevel;
    }

    /*----------------------------------------*
     * Logging
     *----------------------------------------*/

    /**
     * ログを出力する
     *
     * @return void
     */
    public function logging(): void
    {
        // ログローテーションを実行する
        $this->rotateLog();

        // ログを出力するかどうかを判定する
        if (!$this->isLogging()) return;

        // ログを出力するパスを取得する
        $filePath = $this->getDirectory() . DIRECTORY_SEPARATOR . $this->getFileName();

        // message以外の共通フォーマットを置換した文字列を取得する
        $replaceCommonFormat = $this->replaceCommonFormat();

        // ログを出力する
        foreach ($this->messages as $message) {
            // フォーマットをコピーする
            $format = $replaceCommonFormat;

            // messageを置換する
            $text = str_replace(
                LogFormatEnum::MESSAGE->format(),
                $message,
                $format
            );

            // ログを出力する
            file_put_contents(
                $filePath,
                $text . PHP_EOL,
                FILE_APPEND | LOCK_EX
            );
        }
    }

    /**
     * ログを出力するかどうかを判定する
     *
     * @return bool
     */
    protected function isLogging(): bool
    {
        return !empty($this->messages);
    }

    /*----------------------------------------*
     * Log Rotate
     *----------------------------------------*/

    /**
     * ログローテーションを行う
     * 
     * @return void
     */
    protected function rotateLog(): void
    {
        // ログファイルを保持する日数を取得
        $retentionDays = $this->retentionDays();

        // ログファイルを保持する日数が0以下の場合は処理を終了する
        if ($retentionDays <= 0) return;

        // 削除するログファイルの最終更新日時を取得する
        $deleteDate = date("Ymd", strtotime("-{$retentionDays} day"));

        // ログの出力ディレクトリ内のファイルの一覧を取得する
        // 拡張子が変更される可能性があるため、すべてのファイルを取得する
        $files = glob($this->getDirectory() . DIRECTORY_SEPARATOR . "*");

        // ログファイルを保持する日数を超えたファイルを削除する
        foreach ($files as $file) {
            // ファイルの最終更新日時を取得する
            $fileDate = date("Ymd", filemtime($file));

            // ファイルの最終更新日時が削除するログファイルの最終更新日時よりも古い場合は削除する
            if ($fileDate < $deleteDate) {
                unlink($file);
            }
        }
    }

    /**
     * ログファイルを保持する日数を取得する
     * 
     * @return int
     */
    protected function retentionDays(): int
    {
        return $this->env->retentionDays;
    }

    /*----------------------------------------*
     * Messages
     *----------------------------------------*/

    /**
     * 出力するメッセージの配列
     *
     * @var array<int, string>
     */
    protected array $messages = [];

    /**
     * メッセージを追加する
     *
     * @param mixed $message
     * @param mixed $value
     * @return static
     */
    public function add(mixed $message, mixed $value = null, bool $isEmphasis = false): static
    {
        $message = match (true) {
            // $messageがnullの場合は、nullを文字列に変換する
            is_null($message)    => "null",

            // $messageが文字列の場合は、文字列をそのまま返す
            is_string($message)  => $message,

            // $messageが数値の場合は、数値を文字列に変換する
            is_numeric($message) => (string) $message,

            // $messageが真偽値の場合は、真偽値を文字列に変換する
            is_bool($message)    => $message ? "true" : "false",

            // $messageが配列の場合は、配列を文字列に変換する
            is_array($message)   => json_encode($message, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),

            // $messageがオブジェクトの場合は、オブジェクトを文字列に変換する
            is_object($message)  => json_encode($message, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),

            default              => $message,
        };

        $value = match (true) {
            // $messageが文字列の場合は、文字列をそのまま返す
            is_string($value)  => $value,

            // $valueが数値の場合は、数値を文字列に変換する
            is_numeric($value) => (string) $value,

            // $valueが真偽値の場合は、真偽値を文字列に変換する
            is_bool($value)    => $value ? "true" : "false",

            // $valueが配列の場合は、配列を文字列に変換する
            is_array($value)   => json_encode($value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),

            // $valueがオブジェクトの場合は、オブジェクトを文字列に変換する
            is_object($value)  => json_encode($value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),

            default            => $value,
        };

        // $valueがnullでない場合は、$messageと$valueをキーと値の形式で$messageに追加する
        if (!is_null($value)) $message = $message . ": " . $value;

        // $isEmphasisがtrueの場合は、ログに追加するメッセージを強調する
        $this->messages[] = $isEmphasis ? "===== " . $message . " =====" : $message;

        return $this;
    }

    /**
     * メッセージを強調して追加する
     *
     * @param mixed $message
     * @return static
     */
    public function addEmphasis(mixed $message): static
    {
        return $this->addEmpty()->add($message, isEmphasis: true)->addEmpty();
    }

    /**
     * ログに共通部分だけの空の行を追加する
     *
     * @return static
     */
    public function addEmpty(): static
    {
        return $this->add("");
    }

    /**
     * ログに共通部分だけの区切り線を追加する
     *
     * @return static
     */
    public function addDivider(): static
    {
        return $this->addEmpty()->add("===========================")->addEmpty();
    }

    /*----------------------------------------*
     * Format
     *----------------------------------------*/

    /**
     * ログを出力する際のフォーマット
     *
     * @var string
     */
    protected string $format = "";

    /**
     * message以外の共通フォーマットを置換する
     * 
     * @return string
     */
    protected function replaceCommonFormat(): string
    {
        $format = empty($this->format) ? $this->format() : $this->format;

        // stackTraceを設定する
        $this->setStackTrace();

        foreach (LogFormatEnum::cases() as $case) {
            // formatに$caseが含まれていない場合は、次のループに移る
            if (!str_contains($format, $case->format())) continue;

            // messageの場合は、次のループに移る
            if ($case === LogFormatEnum::MESSAGE) continue;

            // 置換する文字列を取得する
            $replace = match ($case) {
                LogFormatEnum::LOG_LEVEL         => $this->logLevel->value,
                LogFormatEnum::DATETIME          => date("Y-m-d H:i:s"),
                LogFormatEnum::FILE_NAME         => $this->stackTraceFileName(),
                LogFormatEnum::LINE_NUMBER       => $this->stackTraceLineNumber(),
                LogFormatEnum::FUNCTION_NAME     => $this->stackTraceFunctionName(),
                LogFormatEnum::CLASS_NAME        => $this->stackTraceClassName(),
                LogFormatEnum::MEMORY_USAGE      => $this->memoryUsage(),
                LogFormatEnum::MEMORY_PEAK_USAGE => $this->memoryPeakUsage(),
            };

            // formatを置換する
            $format = str_replace($case->format(), $replace, $format);
        }

        return $format;
    }

    /**
     * ログを出力する際のフォーマットを取得する
     *
     * @return string
     */
    protected function format(): string
    {
        return $this->env->format;
    }

    /**
     * ログを出力する際のフォーマットを設定する
     *
     * @param \YukataRm\SimpleLogger\Enum\LogFormatEnum|string $format
     * @return static
     */
    public function setFormat(LogFormatEnum|string $format): static
    {
        $this->format = is_string($format) ? $format : $format->format();

        return $this;
    }

    /**
     * ログを出力する際のフォーマットを追加する
     *
     * @param \YukataRm\SimpleLogger\Enum\LogFormatEnum|string $format
     * @return static
     */
    public function addFormat(LogFormatEnum|string $format): static
    {
        // $formatが空の場合は、setFormat()メソッドを実行する
        if (empty($format)) return $this->setFormat($format);

        $this->format .= is_string($format) ? $format : $format->format();

        return $this;
    }

    /*----------------------------------------*
     * Directory
     *----------------------------------------*/

    /**
     * ログファイルの出力先ディレクトリ
     *
     * @var string
     */
    protected string $directory = "";

    /**
     * ログファイルの出力先ディレクトリを取得する
     * 
     * @return string
     */
    protected function getDirectory(): string
    {
        // ログファイルの出力先ディレクトリ
        $directory = empty($this->directory) ? $this->directory() : $this->directory;

        // 出力先ディレクトリを成型する
        $directory = $this->moldDirectory($directory);

        // 出力先ディレクトリが空の場合は、例外を発生させる
        if (empty($directory)) throw new \RuntimeException("output directory is empty.");

        // 出力先ディレクトリが存在しない場合は、ディレクトリを作成する
        if (!file_exists($directory)) mkdir($directory, 0777, true);

        return $directory;
    }

    /**
     * 設定された出力先ディレクトリを成型する
     *
     * @param string $directory
     * @return string
     */
    protected function moldDirectory(string $directory): string
    {
        return $directory;
    }

    /**
     * ログファイルの出力先ディレクトリを取得する
     *
     * @return string
     */
    protected function directory(): string
    {
        return $this->logLevel->value;
    }

    /**
     * ログファイルの出力先ディレクトリを設定する
     *
     * @param string $directory
     * @return static
     */
    public function setDirectory(string $directory): static
    {
        // $directoryが空の場合は、処理を終了する
        if (empty($directory)) return $this;

        $this->directory = $directory;

        return $this;
    }

    /**
     * ログファイルの出力先ディレクトリを追加する
     *
     * @param string $directory
     * @return static
     */
    public function addDirectory(string $directory): static
    {
        // $directoryが空の場合は、処理を終了する
        if (empty($directory)) return $this;

        // $this->directoryが空の場合は、setDirectory()を実行する
        if (empty($this->directory)) return $this->setDirectory($directory);

        $this->directory .= DIRECTORY_SEPARATOR . $directory;

        return $this;
    }

    /*----------------------------------------*
     * File Name
     *----------------------------------------*/

    /**
     * ログファイルのファイル名
     *
     * @var string
     */
    protected string $fileName = "";

    /**
     * ログファイルの拡張子
     *
     * @var string
     */
    protected string $fileExtension = "";

    /**
     * ログファイルのファイル名のフォーマット
     *
     * @var string
     */
    protected string $fileNameFormat = "";

    /**
     * ログファイルのファイル名を取得する
     * 
     * @return string
     */
    protected function getFileName(): string
    {
        // ログファイルのファイル名
        $fileName = empty($this->fileName) ? $this->fileName() : $this->fileName;

        // ログファイルの拡張子
        $fileExtension = empty($this->fileExtension) ? $this->fileExtension() : $this->fileExtension;

        return match (true) {
            empty($fileName) && empty($fileExtension) => throw new \RuntimeException("file name and file extension are empty."),

            empty($fileName)      => ".{$fileExtension}",
            empty($fileExtension) => $fileName,

            default => "{$fileName}.{$fileExtension}",
        };
    }

    /**
     * ログファイルのファイル名を取得する
     *
     * @return string
     */
    protected function fileName(): string
    {
        // ファイル名のフォーマット
        $fileNameFormat = empty($this->fileNameFormat) ? $this->fileNameFormat() : $this->fileNameFormat;

        return (new \DateTime())->format($fileNameFormat);
    }

    /**
     * ログファイルのファイル名を設定する
     *
     * @param string $fileName
     * @return static
     */
    public function setFileName(string $fileName): static
    {
        // $fileNameが空の場合は、処理を終了する
        if (empty($fileName)) return $this;

        $this->fileName = $fileName;

        return $this;
    }

    /**
     * ログファイルのファイル名のフォーマットを取得する
     *
     * @return string
     */
    protected function fileNameFormat(): string
    {
        return $this->env->fileNameFormat;
    }

    /**
     * ログファイルのファイル名のフォーマットを設定する
     *
     * @param string $fileNameFormat
     * @return static
     */
    public function setFileNameFormat(string $fileNameFormat): static
    {
        // $fileNameFormatが空の場合は、処理を終了する
        if (empty($fileNameFormat)) return $this;

        $this->fileNameFormat = $fileNameFormat;

        return $this;
    }

    /**
     * ログファイルの拡張子を取得する
     *
     * @return string
     */
    protected function fileExtension(): string
    {
        return $this->env->fileExtension;
    }

    /**
     * ログファイルの拡張子を設定する
     *
     * @param string $fileExtension
     * @return static
     */
    public function setFileExtension(string $fileExtension): static
    {
        $this->fileExtension = $fileExtension;

        return $this;
    }

    /*----------------------------------------*
     * Memory Usage
     *----------------------------------------*/

    /**
     * メモリ使用量を取得する
     *
     * @return string
     */
    protected function memoryUsage(): string
    {
        $usage = memory_get_usage($this->memoryRealUsage());

        if ($this->useMemoryFormatting()) {
            $usage = $this->formatBytes($usage);
        }

        return $usage;
    }

    /**
     * メモリ使用量の最大値を取得する
     *
     * @return string
     */
    protected function memoryPeakUsage(): string
    {
        $usage = memory_get_peak_usage($this->memoryRealUsage());

        if ($this->useMemoryFormatting()) {
            $usage = $this->formatBytes($usage);
        }

        return $usage;
    }

    /**
     * バイト数をフォーマットする
     *
     * @param float $bytes
     * @return string
     */
    protected function formatBytes(float $bytes): string
    {
        $units = [
            3 => "GB",
            2 => "MB",
            1 => "KB",
            0 => "B",
        ];

        foreach ($units as $pow => $unit) {
            $target = 1024 ** $pow;

            if ($bytes < $target) continue;

            return round($bytes / $target, $this->memoryPrecision()) . $unit;
        }
    }

    /**
     * メモリ使用量を取得する際に、実際に割り当てられたメモリ量を取得するかどうか
     * 
     * @return bool
     */
    protected function memoryRealUsage(): bool
    {
        return $this->env->memoryRealUsage;
    }

    /**
     * メモリ使用量を取得する際に、フォーマットを適用するかどうか
     * 
     * @return bool
     */
    protected function useMemoryFormatting(): bool
    {
        return $this->env->useMemoryFormatting;
    }

    /**
     * メモリ使用量の精度
     * 
     * @return int
     */
    protected function memoryPrecision(): int
    {
        return $this->env->memoryPrecision;
    }

    /*----------------------------------------*
     * Stack Trace
     *----------------------------------------*/

    /**
     * スタックトレース
     *
     * @var array
     */
    protected array $stackTrace = [];

    /**
     * スタックトレースを取得する為のindex
     * 
     * @var int
     */
    protected int $stackTraceIndex = 0;

    /**
     * スタックトレースを設定する
     *
     * @return static
     */
    protected function setStackTrace(): static
    {
        $stackTrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);

        // 0番目は必ずこのメソッド自身なので削除する
        array_shift($stackTrace);

        // 1番目は必ずこのメソッドを呼び出したメソッドなので削除する
        array_shift($stackTrace);

        // スタックトレースを格納する
        $this->stackTrace = $stackTrace;

        return $this;
    }

    /**
     * ログ出力処理を実行したファイルのパスを取得する
     * 
     * @return string
     */
    protected function stackTraceFileName(): string
    {
        return $this->stackTrace[$this->stackTraceIndex]["file"] ?? "";
    }

    /**
     * ログ出力処理を実行したファイルの行番号を取得する
     * 
     * @return int
     */
    protected function stackTraceLineNumber(): int
    {
        return $this->stackTrace[$this->stackTraceIndex]["line"] ?? 0;
    }

    /**
     * ログ出力処理を実行したファイルの関数名を取得する
     * 
     * @return string
     */
    protected function stackTraceFunctionName(): string
    {
        return $this->stackTrace[$this->stackTraceIndex + 1]["function"] ?? "";
    }

    /**
     * ログ出力処理を実行したファイルのクラス名を取得する
     * 
     * @return string
     */
    protected function stackTraceClassName(): string
    {
        return $this->stackTrace[$this->stackTraceIndex + 1]["class"] ?? "";
    }

    /**
     * スタックトレースを取得する為のindexを設定する
     * 
     * @param int $index
     * @return static
     */
    public function setStackTraceIndex(int $index): static
    {
        $this->stackTraceIndex = $index;

        return $this;
    }
}
