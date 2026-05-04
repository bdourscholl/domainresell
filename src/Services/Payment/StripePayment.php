<?php

declare(strict_types=1);

namespace App\Services\Payment;

use App\Core\App;
use App\Core\Logger;

class StripePayment implements PaymentInterface
{
    private array $config;

    public function __construct()
    {
        $this->config = App::getInstance()->config('payments.stripe');
    }

    public function getName(): string
    {
        return 'Stripe';
    }

    public function createPayment(float $amount, string $currency, array $orderData): array
    {
        try {
            \Stripe\Stripe::setApiKey($this->config['secret_key']);

            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => strtolower($currency),
                        'product_data' => ['name' => 'Order #' . ($orderData['order_number'] ?? '')],
                        'unit_amount' => (int) ($amount * 100),
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => url('checkout/success/' . ($orderData['order_id'] ?? '')),
                'cancel_url' => url('checkout/cancel'),
                'metadata' => ['order_id' => $orderData['order_id'] ?? ''],
            ]);

            return ['success' => true, 'session_id' => $session->id, 'url' => $session->url];
        } catch (\Exception $e) {
            Logger::error('Stripe error: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function verifyPayment(array $data): array
    {
        try {
            \Stripe\Stripe::setApiKey($this->config['secret_key']);
            $session = \Stripe\Checkout\Session::retrieve($data['session_id']);

            return [
                'success' => $session->payment_status === 'paid',
                'transaction_id' => $session->payment_intent,
                'amount' => $session->amount_total / 100,
            ];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function getPaymentUrl(array $paymentData): string
    {
        return $paymentData['url'] ?? '';
    }

    public function handleWebhook(array $payload): array
    {
        try {
            \Stripe\Stripe::setApiKey($this->config['secret_key']);
            $sig = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';
            $event = \Stripe\Webhook::constructEvent(
                file_get_contents('php://input'),
                $sig,
                $this->config['webhook_secret']
            );

            if ($event->type === 'checkout.session.completed') {
                $session = $event->data->object;
                return [
                    'success' => true,
                    'order_id' => $session->metadata->order_id ?? null,
                    'transaction_id' => $session->payment_intent,
                    'amount' => $session->amount_total / 100,
                ];
            }

            return ['success' => false, 'error' => 'Unhandled event type'];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function refund(string $transactionId, float $amount): array
    {
        try {
            \Stripe\Stripe::setApiKey($this->config['secret_key']);
            $refund = \Stripe\Refund::create([
                'payment_intent' => $transactionId,
                'amount' => (int) ($amount * 100),
            ]);
            return ['success' => true, 'refund_id' => $refund->id];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
