<?php

declare(strict_types=1);

namespace App\Services\Payment;

use App\Core\App;
use App\Core\Logger;
use GuzzleHttp\Client;

class SslcommerzPayment implements PaymentInterface
{
    private array $config;

    public function __construct()
    {
        $this->config = App::getInstance()->config('payments.sslcommerz');
    }

    public function getName(): string { return 'SSLCommerz'; }

    private function getBaseUrl(): string
    {
        return $this->config['sandbox']
            ? 'https://sandbox.sslcommerz.com'
            : 'https://securepay.sslcommerz.com';
    }

    public function createPayment(float $amount, string $currency, array $orderData): array
    {
        try {
            $client = new Client(['timeout' => 30]);
            $response = $client->post($this->getBaseUrl() . '/gwprocess/v4/api.php', [
                'form_params' => [
                    'store_id' => $this->config['store_id'],
                    'store_passwd' => $this->config['store_password'],
                    'total_amount' => $amount,
                    'currency' => $currency,
                    'tran_id' => $orderData['order_number'] ?? uniqid(),
                    'success_url' => url('webhook/sslcommerz'),
                    'fail_url' => url('checkout/cancel'),
                    'cancel_url' => url('checkout/cancel'),
                    'cus_name' => $orderData['customer_name'] ?? '',
                    'cus_email' => $orderData['customer_email'] ?? '',
                    'cus_phone' => $orderData['customer_phone'] ?? '',
                    'cus_add1' => $orderData['customer_address'] ?? 'N/A',
                    'cus_city' => 'Dhaka',
                    'cus_country' => 'Bangladesh',
                    'shipping_method' => 'NO',
                    'product_name' => 'Domain Registration',
                    'product_category' => 'Domain',
                    'product_profile' => 'non-physical-goods',
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            if (($data['status'] ?? '') === 'SUCCESS') {
                return ['success' => true, 'url' => $data['GatewayPageURL'], 'session_key' => $data['sessionkey']];
            }

            return ['success' => false, 'error' => $data['failedreason'] ?? 'Payment initiation failed'];
        } catch (\Exception $e) {
            Logger::error('SSLCommerz error: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function verifyPayment(array $data): array
    {
        if (($data['status'] ?? '') !== 'VALID') {
            return ['success' => false, 'error' => 'Payment not valid'];
        }
        return ['success' => true, 'transaction_id' => $data['tran_id'] ?? '', 'amount' => (float) ($data['amount'] ?? 0)];
    }

    public function getPaymentUrl(array $paymentData): string { return $paymentData['url'] ?? ''; }
    public function handleWebhook(array $payload): array { return $this->verifyPayment($payload); }
    public function refund(string $transactionId, float $amount): array { return ['success' => false, 'error' => 'Contact SSLCommerz support']; }
}
