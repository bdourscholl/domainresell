<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\App;

class Theme
{
    public static function find(int $id): ?array
    {
        return App::getInstance()->getDb()->queryOne("SELECT * FROM themes WHERE id = ?", [$id]);
    }

    public static function findBySlug(string $slug): ?array
    {
        return App::getInstance()->getDb()->queryOne("SELECT * FROM themes WHERE slug = ?", [$slug]);
    }

    public static function all(): array
    {
        return App::getInstance()->getDb()->query("SELECT * FROM themes ORDER BY name ASC");
    }

    public static function create(array $data): int
    {
        return App::getInstance()->getDb()->insert('themes', $data);
    }

    public static function update(int $id, array $data): int
    {
        return App::getInstance()->getDb()->update('themes', $data, 'id = ?', [$id]);
    }

    public static function delete(int $id): int
    {
        return App::getInstance()->getDb()->delete('themes', 'id = ?', [$id]);
    }

    public static function activateForHome(int $id): void
    {
        $db = App::getInstance()->getDb();
        $db->execute("UPDATE themes SET is_active_home = 0");
        $db->update('themes', ['is_active_home' => 1], 'id = ?', [$id]);
    }

    public static function activateForAccount(int $id): void
    {
        $db = App::getInstance()->getDb();
        $db->execute("UPDATE themes SET is_active_account = 0");
        $db->update('themes', ['is_active_account' => 1], 'id = ?', [$id]);
    }
}
