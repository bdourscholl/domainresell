<?php

declare(strict_types=1);

namespace App\Services\Payment;

use App\Core\App;
use App\Core\Logger;
use GuzzleHttp\Client;

class BkashPayment implements PaymentInterface
{
    private array $config;

    public function __construct()
    {
        $this->config = App::getInstance()->config('payments.bkash');
    }

    public function getName(): string { return 'bKash'; }

    private function getBaseUrl(): string
    {
        return $this->config['sandbox']
            ? 'https://tokenized.sandbox.bka.sh/v1.2.0-beta'
            : 'https://tokenized.pay.bka.sh/v1.2.0-beta';
    }

    private function getToken(): string
    {
        $client = new Client(['timeout' => 30]);
        $response = $client->post($this->getBaseUrl() . '/tokenized/checkout/token/grant', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'username' => $this->config['username'],
                'password' => $this->config['password'],
            ],
            'json' => [
                'app_key' => $this->config['app_key'],
                'app_secret' => $this->config['app_secret'],
            ],
        ]);
        $data = json_decode($response->getBody()->getContents(), true);
        return $data['id_token'] ?? '';
    }

    public function createPayment(float $amount, string $currency, array $orderData): array
    {
        try {
            $token = $this->getToken();
            $client = new Client(['timeout' => 30]);
            $response = $client->post($this->getBaseUrl() . '/tokenized/checkout/create', [
                'headers' => [
                    'Authorization' => $token,
                    'X-APP-Key' => $this->config['app_key'],
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'mode' => '0011',
                    'payerReference' => $orderData['customer_email'] ?? '',
                    'callbackURL' => url('webhook/bkash'),
                    'amount' => number_format($amount, 2, '.', ''),
                    'currency' => 'BDT',
                    'intent' => 'sale',
                    'merchantInvoiceNumber' => $orderData['order_number'] ?? '',
                ],
            ]);
            $data = json_decode($response->getBody()->getContents(), true);

            if (($data['statusCode'] ?? '') === '0000') {
                return ['success' => true, 'url' => $data['bkashURL'], 'payment_id' => $data['paymentID']];
            }

            return ['success' => false, 'error' => $data['statusMessage'] ?? 'bKash payment failed'];
        } catch (\Exception $e) {
            Logger::error('bKash error: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function verifyPayment(array $data): array
    {
        return ['success' => ($data['status'] ?? '') === 'success', 'transaction_id' => $data['trxID'] ?? ''];
    }

    public function getPaymentUrl(array $paymentData): string { return $paymentData['url'] ?? ''; }
    public function handleWebhook(array $payload): array { return $this->verifyPayment($payload); }
    public function refund(string $transactionId, float $amount): array { return ['success' => false, 'error' => 'bKash refund requires manual processing']; }
}
