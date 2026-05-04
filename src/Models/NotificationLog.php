<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\App;

class NotificationLog
{
    public static function create(array $data): int
    {
        return App::getInstance()->getDb()->insert('notification_logs', $data);
    }

    public static function recent(int $limit = 50): array
    {
        return App::getInstance()->getDb()->query(
            "SELECT * FROM notification_logs ORDER BY created_at DESC LIMIT ?",
            [$limit]
        );
    }
}
