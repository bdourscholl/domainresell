<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\App;

class CartItem
{
    public static function find(int $id): ?array
    {
        return App::getInstance()->getDb()->queryOne("SELECT * FROM cart_items WHERE id = ?", [$id]);
    }

    public static function update(int $id, array $data): int
    {
        return App::getInstance()->getDb()->update('cart_items', $data, 'id = ?', [$id]);
    }
}
