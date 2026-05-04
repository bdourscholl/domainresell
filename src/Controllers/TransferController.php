<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\Validator;
use App\Core\View;
use App\Services\CartService;

class TransferController
{
    public function form(Request $request): Response
    {
        return View::renderWithLayout('domain/transfer', [], 'main');
    }

    public function submit(Request $request): Response
    {
        $validator = new Validator($request->all());
        $validator->validate([
            'domain' => 'required',
            'epp_code' => 'required',
        ]);

        if ($validator->getErrors()) {
            flash('error', $validator->getFirstError());
            return Response::redirect('/transfer');
        }

        $domain = $request->post('domain');
        $parts = explode('.', $domain, 2);
        $sld = $parts[0];
        $tld = '.' . ($parts[1] ?? 'com');

        $cartService = new CartService();
        $result = $cartService->addDomain($sld, $tld, 'transfer', 1, $request->post('epp_code'));

        if ($result['success']) {
            flash('success', 'Domain transfer added to cart.');
            return Response::redirect('/cart');
        }

        flash('error', $result['error'] ?? 'Failed to add transfer to cart.');
        return Response::redirect('/transfer');
    }
}
