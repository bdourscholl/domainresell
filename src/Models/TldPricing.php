<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\App;

class TldPricing
{
    public static function find(int $id): ?array
    {
        return App::getInstance()->getDb()->queryOne("SELECT * FROM tld_pricing WHERE id = ?", [$id]);
    }

    public static function findByTld(string $tld): ?array
    {
        return App::getInstance()->getDb()->queryOne("SELECT * FROM tld_pricing WHERE tld = ? AND is_active = 1", [$tld]);
    }

    public static function getAll(bool $activeOnly = true): array
    {
        $sql = "SELECT * FROM tld_pricing";
        if ($activeOnly) {
            $sql .= " WHERE is_active = 1";
        }
        $sql .= " ORDER BY sort_order ASC, tld ASC";
        return App::getInstance()->getDb()->query($sql);
    }

    public static function getFeatured(): array
    {
        return App::getInstance()->getDb()->query(
            "SELECT * FROM tld_pricing WHERE is_featured = 1 AND is_active = 1 ORDER BY sort_order ASC"
        );
    }

    public static function create(array $data): int
    {
        return App::getInstance()->getDb()->insert('tld_pricing', $data);
    }

    public static function update(int $id, array $data): int
    {
        return App::getInstance()->getDb()->update('tld_pricing', $data, 'id = ?', [$id]);
    }

    public static function delete(int $id): int
    {
        return App::getInstance()->getDb()->delete('tld_pricing', 'id = ?', [$id]);
    }
}
