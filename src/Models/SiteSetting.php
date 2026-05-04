<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\App;

class SiteSetting
{
    public static function get(string $key, string $default = ''): string
    {
        $result = App::getInstance()->getDb()->queryOne(
            "SELECT setting_value FROM site_settings WHERE setting_key = ?",
            [$key]
        );
        return $result['setting_value'] ?? $default;
    }

    public static function set(string $key, string $value, string $group = 'general'): void
    {
        $db = App::getInstance()->getDb();
        $existing = $db->queryOne("SELECT id FROM site_settings WHERE setting_key = ?", [$key]);

        if ($existing) {
            $db->update('site_settings', ['setting_value' => $value], 'setting_key = ?', [$key]);
        } else {
            $db->insert('site_settings', [
                'setting_key' => $key,
                'setting_value' => $value,
                'setting_group' => $group,
            ]);
        }
    }

    public static function getByGroup(string $group): array
    {
        return App::getInstance()->getDb()->query(
            "SELECT * FROM site_settings WHERE setting_group = ? ORDER BY setting_key",
            [$group]
        );
    }

    public static function all(): array
    {
        return App::getInstance()->getDb()->query("SELECT * FROM site_settings ORDER BY setting_group, setting_key");
    }
}
