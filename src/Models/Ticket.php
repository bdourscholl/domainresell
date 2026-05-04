<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\App;

class Ticket
{
    public static function find(int $id): ?array
    {
        return App::getInstance()->getDb()->queryOne("SELECT * FROM tickets WHERE id = ?", [$id]);
    }

    public static function findWithUser(int $id): ?array
    {
        return App::getInstance()->getDb()->queryOne(
            "SELECT t.*, u.name as user_name, u.email as user_email FROM tickets t JOIN users u ON t.user_id = u.id WHERE t.id = ?",
            [$id]
        );
    }

    public static function findByUser(int $userId, int $limit = 50): array
    {
        return App::getInstance()->getDb()->query(
            "SELECT * FROM tickets WHERE user_id = ? ORDER BY updated_at DESC LIMIT ?",
            [$userId, $limit]
        );
    }

    public static function create(array $data): int
    {
        return App::getInstance()->getDb()->insert('tickets', $data);
    }

    public static function update(int $id, array $data): int
    {
        return App::getInstance()->getDb()->update('tickets', $data, 'id = ?', [$id]);
    }

    public static function getReplies(int $ticketId): array
    {
        return App::getInstance()->getDb()->query(
            "SELECT tr.*, u.name as user_name, u.role as user_role FROM ticket_replies tr JOIN users u ON tr.user_id = u.id WHERE tr.ticket_id = ? ORDER BY tr.created_at ASC",
            [$ticketId]
        );
    }

    public static function addReply(array $data): int
    {
        return App::getInstance()->getDb()->insert('ticket_replies', $data);
    }

    public static function all(int $limit = 50, string $status = ''): array
    {
        $db = App::getInstance()->getDb();
        $sql = "SELECT t.*, u.name as user_name FROM tickets t JOIN users u ON t.user_id = u.id";
        $params = [];
        if ($status) {
            $sql .= " WHERE t.status = ?";
            $params[] = $status;
        }
        $sql .= " ORDER BY t.updated_at DESC LIMIT ?";
        $params[] = $limit;
        return $db->query($sql, $params);
    }

    public static function countOpen(): int
    {
        return App::getInstance()->getDb()->count('tickets', "status IN ('open', 'in_progress')");
    }
}
