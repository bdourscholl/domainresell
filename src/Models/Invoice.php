<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\App;

class Invoice
{
    public static function find(int $id): ?array
    {
        return App::getInstance()->getDb()->queryOne("SELECT * FROM invoices WHERE id = ?", [$id]);
    }

    public static function findByUser(int $userId, int $limit = 50, int $offset = 0): array
    {
        return App::getInstance()->getDb()->query(
            "SELECT * FROM invoices WHERE user_id = ? ORDER BY created_at DESC LIMIT ? OFFSET ?",
            [$userId, $limit, $offset]
        );
    }

    public static function create(array $data): int
    {
        return App::getInstance()->getDb()->insert('invoices', $data);
    }

    public static function update(int $id, array $data): int
    {
        return App::getInstance()->getDb()->update('invoices', $data, 'id = ?', [$id]);
    }

    public static function all(int $limit = 50, int $offset = 0): array
    {
        return App::getInstance()->getDb()->query(
            "SELECT i.*, u.name as user_name FROM invoices i JOIN users u ON i.user_id = u.id ORDER BY i.created_at DESC LIMIT ? OFFSET ?",
            [$limit, $offset]
        );
    }
}
