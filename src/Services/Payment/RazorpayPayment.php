<?php

declare(strict_types=1);

namespace App\Services\Payment;

use App\Core\App;
use App\Core\Logger;
use GuzzleHttp\Client;

class RazorpayPayment implements PaymentInterface
{
    private array $config;
    private Client $client;

    public function __construct()
    {
        $this->config = App::getInstance()->config('payments.razorpay');
        $this->client = new Client([
            'base_uri' => 'https://api.razorpay.com/v1/',
            'auth' => [$this->config['key_id'], $this->config['key_secret']],
            'timeout' => 30,
        ]);
    }

    public function getName(): string { return 'Razorpay'; }

    public function createPayment(float $amount, string $currency, array $orderData): array
    {
        try {
            $response = $this->client->post('orders', [
                'json' => [
                    'amount' => (int) ($amount * 100),
                    'currency' => $currency,
                    'receipt' => $orderData['order_number'] ?? '',
                ],
            ]);
            $data = json_decode($response->getBody()->getContents(), true);
            return ['success' => true, 'order_id' => $data['id'], 'key_id' => $this->config['key_id'], 'amount' => $data['amount']];
        } catch (\Exception $e) {
            Logger::error('Razorpay error: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function verifyPayment(array $data): array
    {
        $signature = hash_hmac('sha256', $data['razorpay_order_id'] . '|' . $data['razorpay_payment_id'], $this->config['key_secret']);
        $valid = hash_equals($signature, $data['razorpay_signature'] ?? '');
        return ['success' => $valid, 'transaction_id' => $data['razorpay_payment_id'] ?? ''];
    }

    public function getPaymentUrl(array $paymentData): string { return ''; }
    public function handleWebhook(array $payload): array { return ['success' => true, 'data' => $payload]; }

    public function refund(string $transactionId, float $amount): array
    {
        try {
            $this->client->post("payments/{$transactionId}/refund", ['json' => ['amount' => (int) ($amount * 100)]]);
            return ['success' => true];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
