<?php

declare(strict_types=1);

namespace App\Services\Notification;

interface NotificationInterface
{
    public function send(string $message, array $data = []): bool;
    public function isEnabled(): bool;
}
