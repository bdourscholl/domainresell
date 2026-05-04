<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Models\Domain;

class RenewalController
{
    public function index(Request $request): Response
    {
        $userId = current_user_id();
        $domains = Domain::findByUser($userId);
        return View::renderWithLayout('account/renewals', ['domains' => $domains], 'main');
    }

    public function renew(Request $request): Response
    {
        $domainId = (int) $request->param('id');
        $domain = Domain::find($domainId);

        if (!$domain || (int) $domain['user_id'] !== current_user_id()) {
            return Response::redirect('/account/renewals');
        }

        $cartService = new \App\Services\CartService();
        $parts = explode('.', $domain['domain_name'], 2);
        $cartService->addDomain($parts[0], '.' . ($parts[1] ?? ''), 'renew', 1);

        flash('success', 'Domain renewal added to cart.');
        return Response::redirect('/cart');
    }
}
