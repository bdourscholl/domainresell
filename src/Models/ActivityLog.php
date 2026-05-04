<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\App;

class ActivityLog
{
    public static function log(string $action, ?string $description = null, ?string $modelType = null, ?int $modelId = null): void
    {
        $db = App::getInstance()->getDb();
        $db->insert('activity_logs', [
            'user_id' => current_user_id(),
            'action' => $action,
            'description' => $description,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
        ]);
    }

    public static function recent(int $limit = 50): array
    {
        return App::getInstance()->getDb()->query(
            "SELECT al.*, u.name as user_name FROM activity_logs al LEFT JOIN users u ON al.user_id = u.id ORDER BY al.created_at DESC LIMIT ?",
            [$limit]
        );
    }
}
