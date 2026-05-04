<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\App;

class User
{
    public static function find(int $id): ?array
    {
        $db = App::getInstance()->getDb();
        return $db->queryOne("SELECT * FROM users WHERE id = ?", [$id]);
    }

    public static function findByEmail(string $email): ?array
    {
        $db = App::getInstance()->getDb();
        return $db->queryOne("SELECT * FROM users WHERE email = ?", [$email]);
    }

    public static function findBySocialId(string $provider, string $socialId): ?array
    {
        $db = App::getInstance()->getDb();
        return $db->queryOne(
            "SELECT * FROM users WHERE social_provider = ? AND social_id = ?",
            [$provider, $socialId]
        );
    }

    public static function create(array $data): int
    {
        $db = App::getInstance()->getDb();
        return $db->insert('users', $data);
    }

    public static function update(int $id, array $data): int
    {
        $db = App::getInstance()->getDb();
        return $db->update('users', $data, 'id = ?', [$id]);
    }

    public static function all(int $limit = 50, int $offset = 0, string $search = ''): array
    {
        $db = App::getInstance()->getDb();
        $sql = "SELECT * FROM users WHERE role = 'customer'";
        $params = [];

        if ($search) {
            $sql .= " AND (name LIKE ? OR email LIKE ?)";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }

        $sql .= " ORDER BY created_at DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;

        return $db->query($sql, $params);
    }

    public static function count(string $role = 'customer'): int
    {
        $db = App::getInstance()->getDb();
        return $db->count('users', 'role = ?', [$role]);
    }

    public static function generateReferralCode(): string
    {
        return strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));
    }

    public static function findByReferralCode(string $code): ?array
    {
        $db = App::getInstance()->getDb();
        return $db->queryOne("SELECT * FROM users WHERE referral_code = ?", [$code]);
    }

    public static function findByResetToken(string $token): ?array
    {
        $db = App::getInstance()->getDb();
        return $db->queryOne(
            "SELECT * FROM users WHERE reset_token = ? AND reset_token_expires > NOW()",
            [$token]
        );
    }
}
