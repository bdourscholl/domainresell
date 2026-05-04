<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\App;

class Domain
{
    public static function find(int $id): ?array
    {
        $db = App::getInstance()->getDb();
        return $db->queryOne("SELECT * FROM domains WHERE id = ?", [$id]);
    }

    public static function findByUser(int $userId, int $limit = 50, int $offset = 0): array
    {
        $db = App::getInstance()->getDb();
        return $db->query(
            "SELECT * FROM domains WHERE user_id = ? ORDER BY created_at DESC LIMIT ? OFFSET ?",
            [$userId, $limit, $offset]
        );
    }

    public static function findByDomainName(string $domainName): ?array
    {
        $db = App::getInstance()->getDb();
        return $db->queryOne("SELECT * FROM domains WHERE domain_name = ?", [$domainName]);
    }

    public static function create(array $data): int
    {
        $db = App::getInstance()->getDb();
        return $db->insert('domains', $data);
    }

    public static function update(int $id, array $data): int
    {
        $db = App::getInstance()->getDb();
        return $db->update('domains', $data, 'id = ?', [$id]);
    }

    public static function getExpiring(int $days = 30): array
    {
        $db = App::getInstance()->getDb();
        return $db->query(
            "SELECT d.*, u.name as user_name, u.email as user_email
             FROM domains d
             JOIN users u ON d.user_id = u.id
             WHERE d.status = 'active'
             AND d.expiry_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL ? DAY)
             ORDER BY d.expiry_date ASC",
            [$days]
        );
    }

    public static function all(int $limit = 50, int $offset = 0, string $search = ''): array
    {
        $db = App::getInstance()->getDb();
        $sql = "SELECT d.*, u.name as user_name, u.email as user_email FROM domains d JOIN users u ON d.user_id = u.id";
        $params = [];

        if ($search) {
            $sql .= " WHERE d.domain_name LIKE ?";
            $params[] = "%{$search}%";
        }

        $sql .= " ORDER BY d.created_at DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;

        return $db->query($sql, $params);
    }

    public static function countByUser(int $userId): int
    {
        $db = App::getInstance()->getDb();
        return $db->count('domains', 'user_id = ?', [$userId]);
    }

    public static function countAll(): int
    {
        $db = App::getInstance()->getDb();
        return $db->count('domains');
    }
}
