<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Response;
use App\Core\Validator;
use App\Core\View;
use App\Models\Coupon;

class CouponController
{
    public function index(Request $request): Response
    {
        $coupons = Coupon::all();
        return View::renderWithLayout('admin/coupons', ['coupons' => $coupons], 'admin');
    }

    public function store(Request $request): Response
    {
        $validator = new Validator($request->all());
        $validator->validate(['code' => 'required', 'value' => 'required|numeric']);

        if ($validator->getErrors()) {
            flash('error', $validator->getFirstError());
            return Response::redirect('/admin/coupons');
        }

        Coupon::create([
            'code' => strtoupper($request->post('code')),
            'type' => $request->post('type', 'percentage'),
            'value' => $request->post('value'),
            'min_order' => $request->post('min_order') ?: null,
            'max_discount' => $request->post('max_discount') ?: null,
            'max_uses' => $request->post('max_uses') ?: null,
            'valid_from' => $request->post('valid_from') ?: null,
            'valid_until' => $request->post('valid_until') ?: null,
            'is_active' => 1,
        ]);

        flash('success', 'Coupon created.');
        return Response::redirect('/admin/coupons');
    }

    public function delete(Request $request): Response
    {
        Coupon::delete((int) $request->param('id'));
        flash('success', 'Coupon deleted.');
        return Response::redirect('/admin/coupons');
    }
}
