<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\App;

class Cart
{
    public static function findOrCreate(?int $userId, ?string $sessionId): array
    {
        $db = App::getInstance()->getDb();

        if ($userId) {
            $cart = $db->queryOne("SELECT * FROM carts WHERE user_id = ?", [$userId]);
        } else {
            $cart = $db->queryOne("SELECT * FROM carts WHERE session_id = ?", [$sessionId]);
        }

        if (!$cart) {
            $id = $db->insert('carts', [
                'user_id' => $userId,
                'session_id' => $sessionId,
            ]);
            $cart = $db->queryOne("SELECT * FROM carts WHERE id = ?", [$id]);
        }

        return $cart;
    }

    public static function getItems(int $cartId): array
    {
        return App::getInstance()->getDb()->query(
            "SELECT * FROM cart_items WHERE cart_id = ? ORDER BY created_at ASC",
            [$cartId]
        );
    }

    public static function addItem(int $cartId, array $data): int
    {
        $data['cart_id'] = $cartId;
        return App::getInstance()->getDb()->insert('cart_items', $data);
    }

    public static function removeItem(int $itemId): int
    {
        return App::getInstance()->getDb()->delete('cart_items', 'id = ?', [$itemId]);
    }

    public static function clear(int $cartId): int
    {
        return App::getInstance()->getDb()->delete('cart_items', 'cart_id = ?', [$cartId]);
    }

    public static function getTotal(int $cartId): float
    {
        $db = App::getInstance()->getDb();
        $result = $db->queryOne(
            "SELECT COALESCE(SUM(price * years), 0) as total FROM cart_items WHERE cart_id = ?",
            [$cartId]
        );
        return (float) ($result['total'] ?? 0);
    }

    public static function getItemCount(int $cartId): int
    {
        return App::getInstance()->getDb()->count('cart_items', 'cart_id = ?', [$cartId]);
    }

    public static function applyCoupon(int $cartId, ?int $couponId): void
    {
        App::getInstance()->getDb()->update('carts', ['coupon_id' => $couponId], 'id = ?', [$cartId]);
    }

    public static function mergeGuestCart(string $sessionId, int $userId): void
    {
        $db = App::getInstance()->getDb();
        $guestCart = $db->queryOne("SELECT * FROM carts WHERE session_id = ? AND user_id IS NULL", [$sessionId]);

        if (!$guestCart) {
            return;
        }

        $userCart = self::findOrCreate($userId, null);
        $guestItems = self::getItems((int) $guestCart['id']);

        foreach ($guestItems as $item) {
            unset($item['id'], $item['cart_id'], $item['created_at']);
            self::addItem((int) $userCart['id'], $item);
        }

        $db->delete('cart_items', 'cart_id = ?', [$guestCart['id']]);
        $db->delete('carts', 'id = ?', [$guestCart['id']]);
    }
}
