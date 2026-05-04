<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\App;

class Transfer
{
    public static function find(int $id): ?array
    {
        $db = App::getInstance()->getDb();
        return $db->queryOne("SELECT * FROM transfers WHERE id = ?", [$id]);
    }

    public static function findByUser(int $userId): array
    {
        $db = App::getInstance()->getDb();
        return $db->query("SELECT * FROM transfers WHERE user_id = ? ORDER BY created_at DESC", [$userId]);
    }

    public static function create(array $data): int
    {
        $db = App::getInstance()->getDb();
        return $db->insert('transfers', $data);
    }

    public static function update(int $id, array $data): int
    {
        $db = App::getInstance()->getDb();
        return $db->update('transfers', $data, 'id = ?', [$id]);
    }

    public static function getPending(): array
    {
        $db = App::getInstance()->getDb();
        return $db->query("SELECT t.*, u.name, u.email FROM transfers t JOIN users u ON t.user_id = u.id WHERE t.status IN ('pending','processing') ORDER BY t.created_at ASC");
    }
}
