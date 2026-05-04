<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\App;

class TicketReply
{
    public static function create(array $data): int
    {
        return App::getInstance()->getDb()->insert('ticket_replies', $data);
    }
}
