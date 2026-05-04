<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\App;

class WalletTransaction
{
    public static function find(int $id): ?array
    {
        return App::getInstance()->getDb()->queryOne("SELECT * FROM wallet_transactions WHERE id = ?", [$id]);
    }
}
