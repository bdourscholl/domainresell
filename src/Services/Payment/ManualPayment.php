<?php

declare(strict_types=1);

namespace App\Services\Payment;

use App\Core\App;

class ManualPayment implements PaymentInterface
{
    private array $config;

    public function __construct()
    {
        $this->config = App::getInstance()->config('payments.manual');
    }

    public function getName(): string { return 'Manual / Bank Transfer'; }

    public function createPayment(float $amount, string $currency, array $orderData): array
    {
        return [
            'success' => true,
            'requires_manual_confirmation' => true,
            'instructions' => $this->config['instructions'] ?? '',
            'bank_name' => $this->config['bank_name'] ?? '',
            'account_number' => $this->config['account_number'] ?? '',
            'account_name' => $this->config['account_name'] ?? '',
        ];
    }

    public function verifyPayment(array $data): array
    {
        return ['success' => true, 'transaction_id' => $data['reference'] ?? 'MANUAL-' . time()];
    }

    public function getPaymentUrl(array $paymentData): string { return ''; }
    public function handleWebhook(array $payload): array { return ['success' => true]; }
    public function refund(string $transactionId, float $amount): array { return ['success' => true, 'message' => 'Manual refund processed']; }
}
