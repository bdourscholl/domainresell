<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Models\User;
use App\Models\Domain;
use App\Models\Order;

class CustomerController
{
    public function index(Request $request): Response
    {
        $search = $request->get('search', '');
        $customers = User::all(50, 0, $search);
        return View::renderWithLayout('admin/customers', ['customers' => $customers, 'search' => $search], 'admin');
    }

    public function show(Request $request): Response
    {
        $userId = (int) $request->param('id');
        $user = User::find($userId);
        $domains = Domain::findByUser($userId);
        $orders = Order::findByUser($userId);
        return View::renderWithLayout('admin/customer-detail', ['customer' => $user, 'domains' => $domains, 'orders' => $orders], 'admin');
    }

    public function updateStatus(Request $request): Response
    {
        $userId = (int) $request->param('id');
        $status = $request->post('status');
        User::update($userId, ['status' => $status]);
        flash('success', 'Customer status updated.');
        return Response::redirect("/admin/customers/{$userId}");
    }
}
