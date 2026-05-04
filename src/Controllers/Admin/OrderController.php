<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Models\Order;

class OrderController
{
    public function index(Request $request): Response
    {
        $status = $request->get('status', '');
        $orders = Order::all(50, 0, $status);
        return View::renderWithLayout('admin/orders', ['orders' => $orders, 'status' => $status], 'admin');
    }

    public function show(Request $request): Response
    {
        $orderId = (int) $request->param('id');
        $order = Order::find($orderId);
        $items = Order::getItems($orderId);
        return View::renderWithLayout('admin/order-detail', ['order' => $order, 'items' => $items], 'admin');
    }

    public function updateStatus(Request $request): Response
    {
        $orderId = (int) $request->param('id');
        $status = $request->post('status');
        Order::update($orderId, ['status' => $status]);
        flash('success', 'Order status updated.');
        return Response::redirect("/admin/orders/{$orderId}");
    }
}
