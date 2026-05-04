<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\App;

class HomepageStat
{
    public static function getActive(): array
    {
        return App::getInstance()->getDb()->query(
            "SELECT * FROM homepage_stats WHERE is_active = 1 ORDER BY sort_order ASC"
        );
    }

    public static function all(): array
    {
        return App::getInstance()->getDb()->query("SELECT * FROM homepage_stats ORDER BY sort_order ASC");
    }

    public static function update(int $id, array $data): int
    {
        return App::getInstance()->getDb()->update('homepage_stats', $data, 'id = ?', [$id]);
    }

    public static function create(array $data): int
    {
        return App::getInstance()->getDb()->insert('homepage_stats', $data);
    }

    public static function delete(int $id): int
    {
        return App::getInstance()->getDb()->delete('homepage_stats', 'id = ?', [$id]);
    }
}
