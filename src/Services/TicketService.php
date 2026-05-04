<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Ticket;
use App\Services\Notification\NotificationManager;

class TicketService
{
    public function create(int $userId, array $data): int
    {
        $ticketId = Ticket::create([
            'user_id' => $userId,
            'subject' => $data['subject'],
            'department' => $data['department'] ?? 'general',
            'priority' => $data['priority'] ?? 'medium',
            'status' => 'open',
        ]);

        if (!empty($data['message'])) {
            Ticket::addReply([
                'ticket_id' => $ticketId,
                'user_id' => $userId,
                'message' => $data['message'],
                'is_staff' => 0,
            ]);
        }

        $notifier = new NotificationManager();
        $notifier->notifyNewTicket([
            'id' => $ticketId,
            'user_name' => $data['user_name'] ?? '',
            'subject' => $data['subject'],
            'priority' => $data['priority'] ?? 'medium',
        ]);

        return $ticketId;
    }

    public function addReply(int $ticketId, int $userId, string $message, bool $isStaff = false): void
    {
        Ticket::addReply([
            'ticket_id' => $ticketId,
            'user_id' => $userId,
            'message' => $message,
            'is_staff' => $isStaff ? 1 : 0,
        ]);

        Ticket::update($ticketId, [
            'status' => $isStaff ? 'waiting' : 'open',
        ]);
    }
}
