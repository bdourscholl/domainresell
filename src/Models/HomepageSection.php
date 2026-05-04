<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\App;

class HomepageSection
{
    public static function getActive(): array
    {
        return App::getInstance()->getDb()->query(
            "SELECT * FROM homepage_sections WHERE is_active = 1 ORDER BY sort_order ASC"
        );
    }

    public static function all(): array
    {
        return App::getInstance()->getDb()->query("SELECT * FROM homepage_sections ORDER BY sort_order ASC");
    }

    public static function findByKey(string $key): ?array
    {
        return App::getInstance()->getDb()->queryOne("SELECT * FROM homepage_sections WHERE section_key = ?", [$key]);
    }

    public static function update(int $id, array $data): int
    {
        return App::getInstance()->getDb()->update('homepage_sections', $data, 'id = ?', [$id]);
    }
}
