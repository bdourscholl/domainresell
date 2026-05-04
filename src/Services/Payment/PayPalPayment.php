<?php

declare(strict_types=1);

namespace App\Services\Payment;

use App\Core\App;
use App\Core\Logger;
use GuzzleHttp\Client;

class PayPalPayment implements PaymentInterface
{
    private array $config;
    private Client $client;

    public function __construct()
    {
        $this->config = App::getInstance()->config('payments.paypal');
        $baseUri = $this->config['sandbox']
            ? 'https://api-m.sandbox.paypal.com'
            : 'https://api-m.paypal.com';
        $this->client = new Client(['base_uri' => $baseUri, 'timeout' => 30]);
    }

    public function getName(): string { return 'PayPal'; }

    private function getAccessToken(): string
    {
        $response = $this->client->post('/v1/oauth2/token', [
            'auth' => [$this->config['client_id'], $this->config['client_secret']],
            'form_params' => ['grant_type' => 'client_credentials'],
        ]);
        $data = json_decode($response->getBody()->getContents(), true);
        return $data['access_token'];
    }

    public function createPayment(float $amount, string $currency, array $orderData): array
    {
        try {
            $token = $this->getAccessToken();
            $response = $this->client->post('/v2/checkout/orders', [
                'headers' => ['Authorization' => "Bearer {$token}", 'Content-Type' => 'application/json'],
                'json' => [
                    'intent' => 'CAPTURE',
                    'purchase_units' => [[
                        'reference_id' => $orderData['order_number'] ?? '',
                        'amount' => ['currency_code' => $currency, 'value' => number_format($amount, 2, '.', '')],
                    ]],
                    'application_context' => [
                        'return_url' => url('checkout/success/' . ($orderData['order_id'] ?? '')),
                        'cancel_url' => url('checkout/cancel'),
                    ],
                ],
            ]);
            $data = json_decode($response->getBody()->getContents(), true);
            $approveUrl = '';
            foreach ($data['links'] ?? [] as $link) {
                if ($link['rel'] === 'approve') { $approveUrl = $link['href']; break; }
            }
            return ['success' => true, 'order_id' => $data['id'], 'url' => $approveUrl];
        } catch (\Exception $e) {
            Logger::error('PayPal error: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function verifyPayment(array $data): array
    {
        try {
            $token = $this->getAccessToken();
            $response = $this->client->post("/v2/checkout/orders/{$data['order_id']}/capture", [
                'headers' => ['Authorization' => "Bearer {$token}", 'Content-Type' => 'application/json'],
            ]);
            $result = json_decode($response->getBody()->getContents(), true);
            $capture = $result['purchase_units'][0]['payments']['captures'][0] ?? [];
            return [
                'success' => ($result['status'] ?? '') === 'COMPLETED',
                'transaction_id' => $capture['id'] ?? '',
                'amount' => (float) ($capture['amount']['value'] ?? 0),
            ];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function getPaymentUrl(array $paymentData): string { return $paymentData['url'] ?? ''; }

    public function handleWebhook(array $payload): array
    {
        return ['success' => true, 'data' => $payload];
    }

    public function refund(string $transactionId, float $amount): array
    {
        try {
            $token = $this->getAccessToken();
            $this->client->post("/v2/payments/captures/{$transactionId}/refund", [
                'headers' => ['Authorization' => "Bearer {$token}", 'Content-Type' => 'application/json'],
                'json' => ['amount' => ['value' => number_format($amount, 2, '.', ''), 'currency_code' => 'USD']],
            ]);
            return ['success' => true];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
