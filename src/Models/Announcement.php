<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\App;

class Announcement
{
    public static function getActive(): array
    {
        return App::getInstance()->getDb()->query(
            "SELECT * FROM announcements WHERE is_active = 1 AND (starts_at IS NULL OR starts_at <= NOW()) AND (ends_at IS NULL OR ends_at >= NOW()) ORDER BY created_at DESC"
        );
    }

    public static function getHomepageAnnouncements(): array
    {
        return App::getInstance()->getDb()->query(
            "SELECT * FROM announcements WHERE is_active = 1 AND show_on_homepage = 1 AND (starts_at IS NULL OR starts_at <= NOW()) AND (ends_at IS NULL OR ends_at >= NOW()) ORDER BY created_at DESC LIMIT 5"
        );
    }

    public static function all(): array
    {
        return App::getInstance()->getDb()->query("SELECT * FROM announcements ORDER BY created_at DESC");
    }

    public static function find(int $id): ?array
    {
        return App::getInstance()->getDb()->queryOne("SELECT * FROM announcements WHERE id = ?", [$id]);
    }

    public static function create(array $data): int
    {
        return App::getInstance()->getDb()->insert('announcements', $data);
    }

    public static function update(int $id, array $data): int
    {
        return App::getInstance()->getDb()->update('announcements', $data, 'id = ?', [$id]);
    }

    public static function delete(int $id): int
    {
        return App::getInstance()->getDb()->delete('announcements', 'id = ?', [$id]);
    }
}
