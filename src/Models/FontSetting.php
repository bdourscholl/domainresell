<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\App;

class FontSetting
{
    public static function getActive(): ?array
    {
        return App::getInstance()->getDb()->queryOne("SELECT * FROM font_settings WHERE is_active = 1 LIMIT 1");
    }

    public static function all(): array
    {
        return App::getInstance()->getDb()->query("SELECT * FROM font_settings ORDER BY font_name ASC");
    }

    public static function activate(int $id): void
    {
        $db = App::getInstance()->getDb();
        $db->execute("UPDATE font_settings SET is_active = 0");
        $db->update('font_settings', ['is_active' => 1], 'id = ?', [$id]);

        $font = $db->queryOne("SELECT * FROM font_settings WHERE id = ?", [$id]);
        if ($font) {
            SiteSetting::set('active_font', $font['font_family'], 'appearance');
        }
    }

    public static function create(array $data): int
    {
        return App::getInstance()->getDb()->insert('font_settings', $data);
    }
}
