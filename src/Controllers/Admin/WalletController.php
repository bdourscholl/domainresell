<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Services\WalletService;

class WalletController
{
    public function index(Request $request): Response
    {
        return View::renderWithLayout('admin/wallet', [], 'admin');
    }

    public function addFunds(Request $request): Response
    {
        $userId = (int) $request->post('user_id');
        $amount = (float) $request->post('amount');
        $description = $request->post('description', 'Admin credit');

        if ($userId && $amount > 0) {
            $walletService = new WalletService();
            $walletService->credit($userId, $amount, $description, current_user_id());
            flash('success', "Added {$amount} to user wallet.");
        } else {
            flash('error', 'Invalid user or amount.');
        }

        return Response::redirect('/admin/wallet');
    }
}
