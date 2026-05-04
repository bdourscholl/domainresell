<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\App;

class SeoSetting
{
    public static function findByPage(string $pageKey): ?array
    {
        return App::getInstance()->getDb()->queryOne("SELECT * FROM seo_settings WHERE page_key = ?", [$pageKey]);
    }

    public static function all(): array
    {
        return App::getInstance()->getDb()->query("SELECT * FROM seo_settings ORDER BY page_key ASC");
    }

    public static function upsert(string $pageKey, array $data): void
    {
        $db = App::getInstance()->getDb();
        $existing = self::findByPage($pageKey);
        if ($existing) {
            $db->update('seo_settings', $data, 'page_key = ?', [$pageKey]);
        } else {
            $data['page_key'] = $pageKey;
            $db->insert('seo_settings', $data);
        }
    }
}
