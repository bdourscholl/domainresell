<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Wallet;

class WalletService
{
    public function getBalance(int $userId): float
    {
        $wallet = Wallet::findByUser($userId);
        return (float) ($wallet['balance'] ?? 0);
    }

    public function credit(int $userId, float $amount, string $description, ?int $adminId = null): void
    {
        Wallet::credit($userId, $amount, $description, null, null, $adminId);
    }

    public function debit(int $userId, float $amount, string $description): bool
    {
        return Wallet::debit($userId, $amount, $description);
    }

    public function payFromWallet(int $userId, float $amount, int $orderId): bool
    {
        return Wallet::debit($userId, $amount, "Payment for order #{$orderId}", 'order', $orderId);
    }

    public function getTransactions(int $userId, int $limit = 50): array
    {
        return Wallet::getTransactions($userId, $limit);
    }
}
