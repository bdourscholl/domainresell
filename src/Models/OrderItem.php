<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\App;

class OrderItem
{
    public static function create(array $data): int
    {
        return App::getInstance()->getDb()->insert('order_items', $data);
    }

    public static function update(int $id, array $data): int
    {
        return App::getInstance()->getDb()->update('order_items', $data, 'id = ?', [$id]);
    }

    public static function findByOrder(int $orderId): array
    {
        return App::getInstance()->getDb()->query("SELECT * FROM order_items WHERE order_id = ?", [$orderId]);
    }
}
