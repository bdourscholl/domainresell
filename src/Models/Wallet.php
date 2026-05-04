<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\App;

class Wallet
{
    public static function findByUser(int $userId): ?array
    {
        $db = App::getInstance()->getDb();
        $wallet = $db->queryOne("SELECT * FROM wallets WHERE user_id = ?", [$userId]);

        if (!$wallet) {
            $id = $db->insert('wallets', ['user_id' => $userId, 'balance' => 0]);
            $wallet = $db->queryOne("SELECT * FROM wallets WHERE id = ?", [$id]);
        }

        return $wallet;
    }

    public static function credit(int $userId, float $amount, string $description, ?string $refType = null, ?int $refId = null, ?int $createdBy = null): void
    {
        $db = App::getInstance()->getDb();
        $wallet = self::findByUser($userId);
        $newBalance = (float) $wallet['balance'] + $amount;

        $db->update('wallets', ['balance' => $newBalance], 'id = ?', [$wallet['id']]);
        $db->insert('wallet_transactions', [
            'wallet_id' => $wallet['id'],
            'type' => 'credit',
            'amount' => $amount,
            'balance_after' => $newBalance,
            'description' => $description,
            'reference_type' => $refType,
            'reference_id' => $refId,
            'created_by' => $createdBy,
        ]);
    }

    public static function debit(int $userId, float $amount, string $description, ?string $refType = null, ?int $refId = null): bool
    {
        $db = App::getInstance()->getDb();
        $wallet = self::findByUser($userId);

        if ((float) $wallet['balance'] < $amount) {
            return false;
        }

        $newBalance = (float) $wallet['balance'] - $amount;
        $db->update('wallets', ['balance' => $newBalance], 'id = ?', [$wallet['id']]);
        $db->insert('wallet_transactions', [
            'wallet_id' => $wallet['id'],
            'type' => 'debit',
            'amount' => $amount,
            'balance_after' => $newBalance,
            'description' => $description,
            'reference_type' => $refType,
            'reference_id' => $refId,
        ]);

        return true;
    }

    public static function getTransactions(int $userId, int $limit = 50): array
    {
        $db = App::getInstance()->getDb();
        $wallet = self::findByUser($userId);
        return $db->query(
            "SELECT * FROM wallet_transactions WHERE wallet_id = ? ORDER BY created_at DESC LIMIT ?",
            [$wallet['id'], $limit]
        );
    }
}
