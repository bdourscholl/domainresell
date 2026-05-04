<?php

declare(strict_types=1);

namespace App\Services\Notification;

use App\Core\Logger;
use App\Models\NotificationLog;

class NotificationManager
{
    private array $channels = [];

    public function __construct()
    {
        $this->channels = [
            'telegram' => new TelegramNotifier(),
            'whatsapp' => new WhatsAppNotifier(),
            'email' => new EmailNotifier(),
        ];
    }

    public function notify(string $event, string $message, array $data = []): void
    {
        foreach ($this->channels as $channelName => $channel) {
            if (!$channel->isEnabled()) {
                continue;
            }

            try {
                $sent = $channel->send($message, array_merge($data, ['event' => $event]));

                NotificationLog::create([
                    'channel' => $channelName,
                    'event' => $event,
                    'recipient' => $data['recipient'] ?? null,
                    'message' => $message,
                    'status' => $sent ? 'sent' : 'failed',
                ]);
            } catch (\Exception $e) {
                Logger::error("Notification ({$channelName}) failed: " . $e->getMessage());

                NotificationLog::create([
                    'channel' => $channelName,
                    'event' => $event,
                    'message' => $message,
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                ]);
            }
        }
    }

    public function notifyNewOrder(array $order): void
    {
        $message = "🛒 New Order #{$order['order_number']}\n"
            . "Customer: {$order['user_name']}\n"
            . "Total: {$order['total']} {$order['currency']}\n"
            . "Items: {$order['item_count']} domain(s)";

        $this->notify('new_order', $message, $order);
    }

    public function notifyNewTicket(array $ticket): void
    {
        $message = "🎫 New Ticket #{$ticket['id']}\n"
            . "From: {$ticket['user_name']}\n"
            . "Subject: {$ticket['subject']}\n"
            . "Priority: {$ticket['priority']}";

        $this->notify('new_ticket', $message, $ticket);
    }

    public function notifyPaymentReceived(array $payment): void
    {
        $message = "💰 Payment Received\n"
            . "Order: #{$payment['order_number']}\n"
            . "Amount: {$payment['amount']} {$payment['currency']}\n"
            . "Gateway: {$payment['gateway']}";

        $this->notify('payment_received', $message, $payment);
    }

    public function notifyNewVerification(array $verification): void
    {
        $message = "🆔 New KYC Verification\n"
            . "User: {$verification['user_name']}\n"
            . "Document: {$verification['document_type']}";

        $this->notify('new_verification', $message, $verification);
    }

    public function notifyDomainExpiring(array $domain): void
    {
        $message = "⚠️ Domain Expiring\n"
            . "Domain: {$domain['domain_name']}\n"
            . "Expires: {$domain['expiry_date']}\n"
            . "Owner: {$domain['user_name']}";

        $this->notify('domain_expiring', $message, $domain);
    }
}
