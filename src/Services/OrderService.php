<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\App;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Invoice;
use App\Models\Domain;
use App\Services\Notification\NotificationManager;
use App\Services\Registrar\RegistrarFactory;

class OrderService
{
    public function createFromCart(int $userId, array $cartItems, float $subtotal, float $discount, float $total, ?int $couponId, string $paymentMethod): int
    {
        $db = App::getInstance()->getDb();
        $db->beginTransaction();

        try {
            $orderId = Order::create([
                'user_id' => $userId,
                'order_number' => generate_order_number(),
                'status' => 'pending',
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total' => $total,
                'currency' => setting('currency', 'BDT'),
                'coupon_id' => $couponId,
                'payment_method' => $paymentMethod,
                'payment_status' => 'pending',
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $orderId,
                    'domain_name' => $item['domain_name'],
                    'tld' => $item['tld'],
                    'item_type' => $item['item_type'],
                    'years' => $item['years'],
                    'price' => $item['price'],
                    'status' => 'pending',
                ]);
            }

            Invoice::create([
                'user_id' => $userId,
                'order_id' => $orderId,
                'invoice_number' => generate_invoice_number(),
                'status' => 'unpaid',
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total' => $total,
                'currency' => setting('currency', 'BDT'),
                'due_date' => date('Y-m-d', strtotime('+7 days')),
            ]);

            $db->commit();

            $order = Order::find($orderId);
            $notifier = new NotificationManager();
            $notifier->notifyNewOrder([
                'order_number' => $order['order_number'],
                'user_name' => '',
                'total' => $total,
                'currency' => setting('currency', 'BDT'),
                'item_count' => count($cartItems),
            ]);

            return $orderId;
        } catch (\Exception $e) {
            $db->rollback();
            throw $e;
        }
    }

    public function processOrder(int $orderId): void
    {
        $order = Order::find($orderId);
        if (!$order || $order['status'] !== 'pending') return;

        Order::update($orderId, ['status' => 'processing']);
        $items = OrderItem::findByOrder($orderId);

        foreach ($items as $item) {
            $this->processOrderItem($orderId, $item);
        }

        $allCompleted = true;
        $items = OrderItem::findByOrder($orderId);
        foreach ($items as $item) {
            if ($item['status'] !== 'completed') {
                $allCompleted = false;
                break;
            }
        }

        Order::update($orderId, ['status' => $allCompleted ? 'completed' : 'processing']);
    }

    private function processOrderItem(int $orderId, array $item): void
    {
        $order = Order::find($orderId);
        $parts = explode('.', $item['domain_name'], 2);
        $sld = $parts[0];
        $tld = '.' . ($parts[1] ?? '');

        $registrar = RegistrarFactory::create();

        try {
            if ($item['item_type'] === 'register') {
                $result = $registrar->registerDomain($sld, $tld, (int) $item['years'], []);

                if ($result['success'] ?? false) {
                    Domain::create([
                        'user_id' => $order['user_id'],
                        'domain_name' => $item['domain_name'],
                        'tld' => $tld,
                        'registrar' => 'namecheap',
                        'status' => 'active',
                        'registration_date' => date('Y-m-d'),
                        'expiry_date' => date('Y-m-d', strtotime("+{$item['years']} years")),
                    ]);
                    OrderItem::update((int) $item['id'], ['status' => 'completed', 'registrar_order_id' => $result['order_id'] ?? '']);
                } else {
                    OrderItem::update((int) $item['id'], ['status' => 'failed', 'error_message' => $result['error'] ?? '']);
                }
            }
        } catch (\Exception $e) {
            OrderItem::update((int) $item['id'], ['status' => 'failed', 'error_message' => $e->getMessage()]);
        }
    }
}
