<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Models\Order;

class OrderController
{
    public function index(Request $request): Response
    {
        $orders = Order::findByUser(current_user_id());
        return View::renderWithLayout('account/orders', ['orders' => $orders], 'main');
    }

    public function show(Request $request): Response
    {
        $orderId = (int) $request->param('id');
        $order = Order::find($orderId);

        if (!$order || (int) $order['user_id'] !== current_user_id()) {
            flash('error', 'Order not found.');
            return Response::redirect('/account/orders');
        }

        $items = Order::getItems($orderId);
        return View::renderWithLayout('account/order-detail', ['order' => $order, 'items' => $items], 'main');
    }
}
