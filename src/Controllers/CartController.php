<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Services\CartService;
use App\Services\CouponService;

class CartController
{
    public function index(Request $request): Response
    {
        $cartService = new CartService();
        $items = $cartService->getItems();
        $total = $cartService->getTotal();

        return View::renderWithLayout('cart/index', [
            'items' => $items,
            'total' => $total,
        ], 'main');
    }

    public function add(Request $request): Response
    {
        $domain = $request->post('domain');
        $tld = $request->post('tld');
        $type = $request->post('type', 'register');
        $years = (int) $request->post('years', '1');

        $cartService = new CartService();
        $result = $cartService->addDomain($domain, $tld, $type, $years);

        if ($request->isAjax()) {
            return Response::json($result);
        }

        if ($result['success']) {
            flash('success', 'Added to cart!');
        } else {
            flash('error', $result['error'] ?? 'Failed to add to cart.');
        }

        return Response::redirect('/cart');
    }

    public function remove(Request $request): Response
    {
        $itemId = (int) $request->param('id');
        $cartService = new CartService();
        $cartService->removeItem($itemId);

        flash('success', 'Item removed from cart.');
        return Response::redirect('/cart');
    }

    public function applyCoupon(Request $request): Response
    {
        $code = $request->post('coupon_code', '');
        $cartService = new CartService();
        $total = $cartService->getTotal();

        $couponService = new CouponService();
        $result = $couponService->validate($code, $total);

        if ($result['valid']) {
            $cart = $cartService->getCart();
            \App\Models\Cart::applyCoupon((int) $cart['id'], (int) $result['coupon']['id']);
            flash('success', 'Coupon applied! Discount: ' . format_currency($result['discount']));
        } else {
            flash('error', $result['error'] ?? 'Invalid coupon.');
        }

        return Response::redirect('/cart');
    }
}
