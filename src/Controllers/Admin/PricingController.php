<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Response;
use App\Core\Validator;
use App\Core\View;
use App\Models\TldPricing;
use App\Models\ActivityLog;

class PricingController
{
    public function index(Request $request): Response
    {
        $tlds = TldPricing::getAll(false);
        return View::renderWithLayout('admin/pricing', ['tlds' => $tlds], 'admin');
    }

    public function store(Request $request): Response
    {
        $validator = new Validator($request->all());
        $validator->validate([
            'tld' => 'required',
            'register_price' => 'required|numeric',
            'renew_price' => 'required|numeric',
            'transfer_price' => 'required|numeric',
        ]);

        if ($validator->getErrors()) {
            flash('error', $validator->getFirstError());
            return Response::redirect('/admin/pricing');
        }

        TldPricing::create([
            'tld' => $request->post('tld'),
            'register_price' => $request->post('register_price'),
            'renew_price' => $request->post('renew_price'),
            'transfer_price' => $request->post('transfer_price'),
            'registrar' => $request->post('registrar', 'namecheap'),
            'is_featured' => $request->post('is_featured', '0') === '1' ? 1 : 0,
            'is_active' => 1,
        ]);

        ActivityLog::log('pricing.created', "Added TLD {$request->post('tld')}");
        flash('success', 'TLD pricing added.');
        return Response::redirect('/admin/pricing');
    }

    public function update(Request $request): Response
    {
        $id = (int) $request->param('id');
        TldPricing::update($id, [
            'register_price' => $request->post('register_price'),
            'renew_price' => $request->post('renew_price'),
            'transfer_price' => $request->post('transfer_price'),
            'is_featured' => $request->post('is_featured', '0') === '1' ? 1 : 0,
            'is_active' => $request->post('is_active', '0') === '1' ? 1 : 0,
        ]);

        flash('success', 'Pricing updated.');
        return Response::redirect('/admin/pricing');
    }

    public function delete(Request $request): Response
    {
        $id = (int) $request->param('id');
        TldPricing::delete($id);
        flash('success', 'TLD removed.');
        return Response::redirect('/admin/pricing');
    }
}
