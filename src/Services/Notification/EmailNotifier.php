<?php

declare(strict_types=1);

namespace App\Services\Notification;

use App\Services\EmailService;

class EmailNotifier implements NotificationInterface
{
    private bool $enabled;

    public function __construct()
    {
        $this->enabled = setting('email_enabled', '1') === '1';
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function send(string $message, array $data = []): bool
    {
        if (!$this->isEnabled()) {
            return false;
        }

        $recipient = $data['recipient'] ?? $data['email'] ?? null;
        $subject = $data['subject'] ?? 'Notification';

        if (!$recipient) {
            return false;
        }

        $emailService = new EmailService();
        return $emailService->send($recipient, $subject, $message);
    }
}
