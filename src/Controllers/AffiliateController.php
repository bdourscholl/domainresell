<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Models\User;
use App\Services\AffiliateService;

class AffiliateController
{
    public function index(Request $request): Response
    {
        $affiliateService = new AffiliateService();
        $affiliate = $affiliateService->getOrCreate(current_user_id());
        $user = User::find(current_user_id());
        $transactions = \App\Models\Affiliate::getTransactions((int) $affiliate['id']);

        return View::renderWithLayout('account/affiliate', [
            'affiliate' => $affiliate,
            'referral_code' => $user['referral_code'] ?? '',
            'transactions' => $transactions,
        ], 'main');
    }
}
