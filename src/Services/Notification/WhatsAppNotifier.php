<?php

declare(strict_types=1);

namespace App\Services\Notification;

use App\Core\Logger;
use GuzzleHttp\Client;

class WhatsAppNotifier implements NotificationInterface
{
    private string $apiUrl;
    private string $apiToken;
    private string $phoneNumberId;
    private string $adminPhone;
    private bool $enabled;

    public function __construct()
    {
        $this->apiUrl = $_ENV['WHATSAPP_API_URL'] ?? 'https://graph.facebook.com/v17.0';
        $this->apiToken = $_ENV['WHATSAPP_API_TOKEN'] ?? '';
        $this->phoneNumberId = $_ENV['WHATSAPP_PHONE_NUMBER_ID'] ?? '';
        $this->adminPhone = $_ENV['WHATSAPP_ADMIN_PHONE'] ?? '';
        $this->enabled = setting('whatsapp_enabled', '0') === '1' && $this->apiToken && $this->phoneNumberId;
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

        $recipient = $data['recipient'] ?? $this->adminPhone;
        if (!$recipient) {
            return false;
        }

        try {
            $client = new Client(['timeout' => 10]);
            $response = $client->post("{$this->apiUrl}/{$this->phoneNumberId}/messages", [
                'headers' => [
                    'Authorization' => "Bearer {$this->apiToken}",
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'messaging_product' => 'whatsapp',
                    'to' => $recipient,
                    'type' => 'text',
                    'text' => ['body' => $message],
                ],
            ]);

            return $response->getStatusCode() === 200;
        } catch (\Exception $e) {
            Logger::error('WhatsApp notification failed: ' . $e->getMessage());
            return false;
        }
    }
}
