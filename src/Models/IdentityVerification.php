<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\App;

class IdentityVerification
{
    public static function find(int $id): ?array
    {
        $db = App::getInstance()->getDb();
        return $db->queryOne("SELECT * FROM identity_verifications WHERE id = ?", [$id]);
    }

    public static function findByUser(int $userId): ?array
    {
        $db = App::getInstance()->getDb();
        return $db->queryOne(
            "SELECT * FROM identity_verifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 1",
            [$userId]
        );
    }

    public static function create(array $data): int
    {
        $db = App::getInstance()->getDb();
        return $db->insert('identity_verifications', $data);
    }

    public static function update(int $id, array $data): int
    {
        $db = App::getInstance()->getDb();
        return $db->update('identity_verifications', $data, 'id = ?', [$id]);
    }

    public static function getPending(): array
    {
        $db = App::getInstance()->getDb();
        return $db->query(
            "SELECT iv.*, u.name, u.email FROM identity_verifications iv
             JOIN users u ON iv.user_id = u.id
             WHERE iv.status IN ('pending', 'under_review')
             ORDER BY iv.created_at ASC"
        );
    }
}
