<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\App;

class DnsRecord
{
    public static function findByDomain(int $domainId): array
    {
        $db = App::getInstance()->getDb();
        return $db->query(
            "SELECT * FROM dns_records WHERE domain_id = ? ORDER BY record_type, name",
            [$domainId]
        );
    }

    public static function find(int $id): ?array
    {
        $db = App::getInstance()->getDb();
        return $db->queryOne("SELECT * FROM dns_records WHERE id = ?", [$id]);
    }

    public static function create(array $data): int
    {
        $db = App::getInstance()->getDb();
        return $db->insert('dns_records', $data);
    }

    public static function update(int $id, array $data): int
    {
        $db = App::getInstance()->getDb();
        return $db->update('dns_records', $data, 'id = ?', [$id]);
    }

    public static function delete(int $id): int
    {
        $db = App::getInstance()->getDb();
        return $db->delete('dns_records', 'id = ?', [$id]);
    }
}
