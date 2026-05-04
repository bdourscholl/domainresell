<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\App;

class Payment
{
    public static function find(int $id): ?array
    {
        return App::getInstance()->getDb()->queryOne("SELECT * FROM payments WHERE id = ?", [$id]);
    }

    public static function findByTransaction(string $transactionId): ?array
    {
        return App::getInstance()->getDb()->queryOne("SELECT * FROM payments WHERE transaction_id = ?", [$transactionId]);
    }

    public static function create(array $data): int
    {
        return App::getInstance()->getDb()->insert('payments', $data);
    }

    public static function update(int $id, array $data): int
    {
        return App::getInstance()->getDb()->update('payments', $data, 'id = ?', [$id]);
    }
}
