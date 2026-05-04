<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Services\WalletService;

class WalletController
{
    public function index(Request $request): Response
    {
        $walletService = new WalletService();
        $balance = $walletService->getBalance(current_user_id());
        $transactions = $walletService->getTransactions(current_user_id());

        return View::renderWithLayout('account/wallet', [
            'balance' => $balance,
            'transactions' => $transactions,
        ], 'main');
    }
}
