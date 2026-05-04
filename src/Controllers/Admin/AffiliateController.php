<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Models\Affiliate;
use App\Models\SiteSetting;

class AffiliateController
{
    public function index(Request $request): Response
    {
        $affiliates = Affiliate::all();
        return View::renderWithLayout('admin/affiliates', ['affiliates' => $affiliates], 'admin');
    }

    public function updateSettings(Request $request): Response
    {
        SiteSetting::set('affiliate_enabled', $request->post('affiliate_enabled', '0'), 'affiliate');
        SiteSetting::set('affiliate_commission_rate', $request->post('commission_rate', '10'), 'affiliate');
        flash('success', 'Affiliate settings updated.');
        return Response::redirect('/admin/affiliates');
    }
}
