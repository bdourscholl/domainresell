<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\App;

class DomainContact
{
    public static function findByUser(int $userId): array
    {
        return App::getInstance()->getDb()->query("SELECT * FROM domain_contacts WHERE user_id = ?", [$userId]);
    }

    public static function getDefault(int $userId): ?array
    {
        return App::getInstance()->getDb()->queryOne(
            "SELECT * FROM domain_contacts WHERE user_id = ? AND is_default = 1 LIMIT 1",
            [$userId]
        );
    }

    public static function create(array $data): int
    {
        return App::getInstance()->getDb()->insert('domain_contacts', $data);
    }

    public static function update(int $id, array $data): int
    {
        return App::getInstance()->getDb()->update('domain_contacts', $data, 'id = ?', [$id]);
    }
}
