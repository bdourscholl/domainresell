<?php

declare(strict_types=1);

namespace App\Services\Payment;

use App\Core\App;
use App\Core\Logger;

class NagadPayment implements PaymentInterface
{
    private array $config;

    public function __construct()
    {
        $this->config = App::getInstance()->config('payments.nagad');
    }

    public function getName(): string { return 'Nagad'; }

    public function createPayment(float $amount, string $currency, array $orderData): array
    {
        // Nagad payment initialization
        // Implementation depends on Nagad's specific API version
        return [
            'success' => true,
            'url' => '#nagad-redirect',
            'message' => 'Nagad payment initialized',
        ];
    }

    public function verifyPayment(array $data): array
    {
        return ['success' => true, 'transaction_id' => $data['payment_ref_id'] ?? ''];
    }

    public function getPaymentUrl(array $paymentData): string { return $paymentData['url'] ?? ''; }
    public function handleWebhook(array $payload): array { return $this->verifyPayment($payload); }
    public function refund(string $transactionId, float $amount): array { return ['success' => false, 'error' => 'Nagad refund requires manual processing']; }
}
