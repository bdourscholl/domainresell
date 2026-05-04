<?php

declare(strict_types=1);

namespace App\Services\Notification;

use App\Core\Logger;
use GuzzleHttp\Client;

class TelegramNotifier implements NotificationInterface
{
    private string $botToken;
    private string $chatId;
    private bool $enabled;

    public function __construct()
    {
        $this->botToken = $_ENV['TELEGRAM_BOT_TOKEN'] ?? '';
        $this->chatId = $_ENV['TELEGRAM_CHAT_ID'] ?? '';
        $this->enabled = setting('telegram_enabled', '0') === '1' && $this->botToken && $this->chatId;
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

        try {
            $client = new Client(['timeout' => 10]);
            $response = $client->post("https://api.telegram.org/bot{$this->botToken}/sendMessage", [
                'json' => [
                    'chat_id' => $this->chatId,
                    'text' => $message,
                    'parse_mode' => 'HTML',
                    'disable_web_page_preview' => true,
                ],
            ]);

            return $response->getStatusCode() === 200;
        } catch (\Exception $e) {
            Logger::error('Telegram notification failed: ' . $e->getMessage());
            return false;
        }
    }
}
