<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Models\Coupon;
use App\Services\CartService;
use App\Services\OrderService;
use App\Services\CouponService;
use App\Services\Payment\PaymentFactory;

class CheckoutController
{
    public function index(Request $request): Response
    {
        $cartService = new CartService();
        $items = $cartService->getItems();

        if (empty($items)) {
            flash('error', 'Your cart is empty.');
            return Response::redirect('/cart');
        }

        $total = $cartService->getTotal();
        $enabledGateways = PaymentFactory::getEnabled();

        return View::renderWithLayout('cart/checkout', [
            'items' => $items,
            'total' => $total,
            'gateways' => $enabledGateways,
        ], 'main');
    }

    public function process(Request $request): Response
    {
        $cartService = new CartService();
        $items = $cartService->getItems();

        if (empty($items)) {
            flash('error', 'Your cart is empty.');
            return Response::redirect('/cart');
        }

        $subtotal = $cartService->getTotal();
        $discount = 0;
        $couponId = null;
        $cart = $cartService->getCart();

        if ($cart['coupon_id']) {
            $coupon = Coupon::find((int) $cart['coupon_id']);
            if ($coupon) {
                $discount = Coupon::calculateDiscount($coupon, $subtotal);
                $couponId = (int) $coupon['id'];
            }
        }

        $total = $subtotal - $discount;
        $paymentMethod = $request->post('payment_method', 'manual');
        $userId = current_user_id();

        $orderService = new OrderService();
        $orderId = $orderService->createFromCart($userId, $items, $subtotal, $discount, $total, $couponId, $paymentMethod);

        if ($couponId) {
            $couponService = new CouponService();
            $couponService->apply($couponId);
        }

        $cartService->clear();

        if ($paymentMethod === 'manual') {
            flash('success', 'Order placed! Please complete your bank transfer.');
            return Response::redirect("/account/orders/{$orderId}");
        }

        try {
            $gateway = PaymentFactory::create($paymentMethod);
            $payment = $gateway->createPayment($total, setting('currency', 'BDT'), [
                'order_id' => $orderId,
                'order_number' => \App\Models\Order::find($orderId)['order_number'] ?? '',
            ]);

            if ($payment['success'] && ($payment['url'] ?? '')) {
                return Response::redirect($payment['url']);
            }
        } catch (\Exception $e) {
            flash('error', 'Payment processing failed. Your order has been saved.');
        }

        return Response::redirect("/account/orders/{$orderId}");
    }

    public function success(Request $request): Response
    {
        $orderId = (int) $request->param('id');
        flash('success', 'Payment successful! Your order is being processed.');
        return Response::redirect("/account/orders/{$orderId}");
    }

    public function cancel(Request $request): Response
    {
        flash('error', 'Payment was cancelled.');
        return Response::redirect('/cart');
    }
}
