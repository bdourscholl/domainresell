<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Models\User;
use App\Models\Domain;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\ActivityLog;

class DashboardController
{
    public function index(Request $request): Response
    {
        return View::renderWithLayout('admin/dashboard', [
            'total_customers' => User::count('customer'),
            'total_domains' => Domain::countAll(),
            'total_orders' => Order::countAll(),
            'total_revenue' => Order::totalRevenue(),
            'open_tickets' => Ticket::countOpen(),
            'recent_orders' => Order::recentOrders(10),
            'recent_activity' => ActivityLog::recent(10),
        ], 'admin');
    }
}
