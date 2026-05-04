<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\Logger;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Invoice;
use App\Services\Payment\PaymentFactory;
use App\Services\OrderService;

class WebhookController
{
    public function stripe(Request $request): Response
    {
        return $this->handleWebhook('stripe', $request);
    }

    public function paypal(Request $request): Response
    {
        return $this->handleWebhook('paypal', $request);
    }

    public function razorpay(Request $request): Response
    {
        return $this->handleWebhook('razorpay', $request);
    }

    public function sslcommerz(Request $request): Response
    {
        return $this->handleWebhook('sslcommerz', $request);
    }

    public function bkash(Request $request): Response
    {
        return $this->handleWebhook('bkash', $request);
    }

    public function nagad(Request $request): Response
    {
        return $this->handleWebhook('nagad', $request);
    }

    private function handleWebhook(string $gateway, Request $request): Response
    {
        try {
            $paymentGateway = PaymentFactory::create($gateway);
            $payload = $request->all();
            $result = $paymentGateway->handleWebhook($payload);

            if ($result['success'] && !empty($result['order_id'])) {
                $orderId = (int) $result['order_id'];
                $order = Order::find($orderId);

                if ($order) {
                    Payment::create([
                        'user_id' => $order['user_id'],
                        'order_id' => $orderId,
                        'gateway' => $gateway,
                        'transaction_id' => $result['transaction_id'] ?? '',
                        'amount' => $result['amount'] ?? $order['total'],
                        'currency' => $order['currency'],
                        'status' => 'completed',
                        'gateway_response' => json_encode($payload),
                    ]);

                    Order::update($orderId, ['payment_status' => 'paid']);

                    $invoice = \App\Core\App::getInstance()->getDb()->queryOne(
                        "SELECT id FROM invoices WHERE order_id = ?",
                        [$orderId]
                    );
                    if ($invoice) {
                        Invoice::update((int) $invoice['id'], ['status' => 'paid', 'paid_at' => date('Y-m-d H:i:s')]);
                    }

                    $orderService = new OrderService();
                    $orderService->processOrder($orderId);
                }
            }

            return Response::json(['status' => 'ok']);
        } catch (\Exception $e) {
            Logger::error("Webhook ({$gateway}) error: " . $e->getMessage());
            return Response::json(['error' => 'Webhook processing failed'], 500);
        }
    }
}
