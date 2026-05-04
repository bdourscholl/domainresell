<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Models\SiteSetting;

class PaymentController
{
    public function index(Request $request): Response
    {
        $gateways = \App\Core\App::getInstance()->config('payments');
        return View::renderWithLayout('admin/payment-gateways', ['gateways' => $gateways], 'admin');
    }

    public function update(Request $request): Response
    {
        // Save payment gateway settings to site_settings
        $settings = $request->post('settings', []);
        if (is_array($settings)) {
            foreach ($settings as $key => $value) {
                SiteSetting::set("payment_{$key}", (string) $value, 'payment');
            }
        }
        flash('success', 'Payment gateway settings updated.');
        return Response::redirect('/admin/payments');
    }
}
