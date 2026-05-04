<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\App;

class Coupon
{
    public static function find(int $id): ?array
    {
        return App::getInstance()->getDb()->queryOne("SELECT * FROM coupons WHERE id = ?", [$id]);
    }

    public static function findByCode(string $code): ?array
    {
        return App::getInstance()->getDb()->queryOne("SELECT * FROM coupons WHERE code = ? AND is_active = 1", [$code]);
    }

    public static function create(array $data): int
    {
        return App::getInstance()->getDb()->insert('coupons', $data);
    }

    public static function update(int $id, array $data): int
    {
        return App::getInstance()->getDb()->update('coupons', $data, 'id = ?', [$id]);
    }

    public static function delete(int $id): int
    {
        return App::getInstance()->getDb()->delete('coupons', 'id = ?', [$id]);
    }

    public static function all(): array
    {
        return App::getInstance()->getDb()->query("SELECT * FROM coupons ORDER BY created_at DESC");
    }

    public static function isValid(array $coupon, float $orderTotal): bool
    {
        if (!$coupon['is_active']) return false;
        if ($coupon['valid_from'] && strtotime($coupon['valid_from']) > time()) return false;
        if ($coupon['valid_until'] && strtotime($coupon['valid_until']) < time()) return false;
        if ($coupon['max_uses'] && $coupon['used_count'] >= $coupon['max_uses']) return false;
        if ($coupon['min_order'] && $orderTotal < (float) $coupon['min_order']) return false;
        return true;
    }

    public static function calculateDiscount(array $coupon, float $total): float
    {
        $discount = $coupon['type'] === 'percentage'
            ? $total * ((float) $coupon['value'] / 100)
            : (float) $coupon['value'];

        if ($coupon['max_discount'] && $discount > (float) $coupon['max_discount']) {
            $discount = (float) $coupon['max_discount'];
        }

        return min($discount, $total);
    }

    public static function incrementUsage(int $id): void
    {
        App::getInstance()->getDb()->execute("UPDATE coupons SET used_count = used_count + 1 WHERE id = ?", [$id]);
    }
}
