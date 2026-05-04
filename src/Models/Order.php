<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\App;

class Order
{
    public static function find(int $id): ?array
    {
        $db = App::getInstance()->getDb();
        return $db->queryOne("SELECT * FROM orders WHERE id = ?", [$id]);
    }

    public static function findByOrderNumber(string $orderNumber): ?array
    {
        $db = App::getInstance()->getDb();
        return $db->queryOne("SELECT * FROM orders WHERE order_number = ?", [$orderNumber]);
    }

    public static function findByUser(int $userId, int $limit = 50, int $offset = 0): array
    {
        $db = App::getInstance()->getDb();
        return $db->query(
            "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC LIMIT ? OFFSET ?",
            [$userId, $limit, $offset]
        );
    }

    public static function create(array $data): int
    {
        $db = App::getInstance()->getDb();
        return $db->insert('orders', $data);
    }

    public static function update(int $id, array $data): int
    {
        $db = App::getInstance()->getDb();
        return $db->update('orders', $data, 'id = ?', [$id]);
    }

    public static function all(int $limit = 50, int $offset = 0, string $status = ''): array
    {
        $db = App::getInstance()->getDb();
        $sql = "SELECT o.*, u.name as user_name, u.email as user_email FROM orders o JOIN users u ON o.user_id = u.id";
        $params = [];

        if ($status) {
            $sql .= " WHERE o.status = ?";
            $params[] = $status;
        }

        $sql .= " ORDER BY o.created_at DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;

        return $db->query($sql, $params);
    }

    public static function getItems(int $orderId): array
    {
        $db = App::getInstance()->getDb();
        return $db->query("SELECT * FROM order_items WHERE order_id = ?", [$orderId]);
    }

    public static function countAll(): int
    {
        return App::getInstance()->getDb()->count('orders');
    }

    public static function totalRevenue(): float
    {
        $db = App::getInstance()->getDb();
        $result = $db->queryOne("SELECT COALESCE(SUM(total), 0) as total FROM orders WHERE payment_status = 'paid'");
        return (float) ($result['total'] ?? 0);
    }

    public static function recentOrders(int $limit = 10): array
    {
        $db = App::getInstance()->getDb();
        return $db->query(
            "SELECT o.*, u.name as user_name FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC LIMIT ?",
            [$limit]
        );
    }
}
