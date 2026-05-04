<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Session;
use App\Models\Cart;
use App\Models\TldPricing;

class CartService
{
    public function getCart(): array
    {
        $session = new Session();
        $userId = $session->getUserId();
        $sessionId = session_id();

        return Cart::findOrCreate($userId, $sessionId);
    }

    public function addDomain(string $domain, string $tld, string $type = 'register', int $years = 1, ?string $eppCode = null): array
    {
        $cart = $this->getCart();
        $pricing = TldPricing::findByTld($tld);

        if (!$pricing) {
            return ['success' => false, 'error' => 'TLD not available'];
        }

        $price = match ($type) {
            'transfer' => (float) $pricing['transfer_price'],
            'renew' => (float) $pricing['renew_price'],
            default => (float) $pricing['register_price'],
        };

        Cart::addItem((int) $cart['id'], [
            'domain_name' => $domain,
            'tld' => $tld,
            'item_type' => $type,
            'years' => $years,
            'price' => $price,
            'epp_code' => $eppCode,
        ]);

        return ['success' => true];
    }

    public function removeItem(int $itemId): void
    {
        Cart::removeItem($itemId);
    }

    public function getItems(): array
    {
        $cart = $this->getCart();
        return Cart::getItems((int) $cart['id']);
    }

    public function getTotal(): float
    {
        $cart = $this->getCart();
        return Cart::getTotal((int) $cart['id']);
    }

    public function getItemCount(): int
    {
        $cart = $this->getCart();
        return Cart::getItemCount((int) $cart['id']);
    }

    public function clear(): void
    {
        $cart = $this->getCart();
        Cart::clear((int) $cart['id']);
    }
}
