<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\App;

class AffiliateTransaction
{
    public static function create(array $data): int
    {
        return App::getInstance()->getDb()->insert('affiliate_transactions', $data);
    }
}
