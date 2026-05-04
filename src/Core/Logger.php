<?php

declare(strict_types=1);

namespace App\Core;

class Logger
{
    private static string $logPath = '';

    private static function getLogPath(): string
    {
        if (!self::$logPath) {
            self::$logPath = (defined('BASE_PATH') ? BASE_PATH : dirname(__DIR__, 2)) . '/storage/logs';
        }
        return self::$logPath;
    }

    public static function info(string $message, array $context = []): void
    {
        self::log('INFO', $message, $context);
    }

    public static function error(string $message, array $context = []): void
    {
        self::log('ERROR', $message, $context);
    }

    public static function warning(string $message, array $context = []): void
    {
        self::log('WARNING', $message, $context);
    }

    public static function debug(string $message, array $context = []): void
    {
        self::log('DEBUG', $message, $context);
    }

    private static function log(string $level, string $message, array $context): void
    {
        $logDir = self::getLogPath();
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }

        $date = date('Y-m-d');
        $time = date('Y-m-d H:i:s');
        $contextStr = $context ? ' ' . json_encode($context, JSON_UNESCAPED_UNICODE) : '';
        $line = "[{$time}] [{$level}] {$message}{$contextStr}" . PHP_EOL;

        file_put_contents("{$logDir}/{$date}.log", $line, FILE_APPEND | LOCK_EX);
    }
}
