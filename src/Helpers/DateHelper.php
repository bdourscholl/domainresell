<?php

declare(strict_types=1);

namespace App\Helpers;

class DateHelper
{
    public static function format(string $date, string $format = 'M j, Y'): string
    {
        return date($format, strtotime($date));
    }

    public static function formatDateTime(string $date, string $format = 'M j, Y g:i A'): string
    {
        return date($format, strtotime($date));
    }

    public static function daysUntil(string $date): int
    {
        $target = strtotime($date);
        $now = time();
        $diff = $target - $now;
        return max(0, (int) floor($diff / 86400));
    }

    public static function isExpired(string $date): bool
    {
        return strtotime($date) < time();
    }

    public static function isExpiringSoon(string $date, int $days = 30): bool
    {
        $daysLeft = self::daysUntil($date);
        return $daysLeft > 0 && $daysLeft <= $days;
    }
}
