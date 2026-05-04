<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\App;

class Affiliate
{
    public static function findByUser(int $userId): ?array
    {
        return App::getInstance()->getDb()->queryOne("SELECT * FROM affiliates WHERE user_id = ?", [$userId]);
    }

    public static function create(array $data): int
    {
        return App::getInstance()->getDb()->insert('affiliates', $data);
    }

    public static function update(int $id, array $data): int
    {
        return App::getInstance()->getDb()->update('affiliates', $data, 'id = ?', [$id]);
    }

    public static function getTransactions(int $affiliateId, int $limit = 50): array
    {
        return App::getInstance()->getDb()->query(
            "SELECT * FROM affiliate_transactions WHERE affiliate_id = ? ORDER BY created_at DESC LIMIT ?",
            [$affiliateId, $limit]
        );
    }

    public static function all(): array
    {
        return App::getInstance()->getDb()->query(
            "SELECT a.*, u.name, u.email FROM affiliates a JOIN users u ON a.user_id = u.id ORDER BY a.created_at DESC"
        );
    }
}
