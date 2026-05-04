<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Models\Domain;
use App\Services\WhoisPrivacyService;

class PrivacyController
{
    public function index(Request $request): Response
    {
        $domainId = (int) $request->param('id');
        $domain = Domain::find($domainId);

        if (!$domain || (int) $domain['user_id'] !== current_user_id()) {
            flash('error', 'Domain not found.');
            return Response::redirect('/account/domains');
        }

        return View::renderWithLayout('account/whois-privacy', ['domain' => $domain], 'main');
    }

    public function toggle(Request $request): Response
    {
        $domainId = (int) $request->param('id');
        $domain = Domain::find($domainId);

        if (!$domain || (int) $domain['user_id'] !== current_user_id()) {
            return Response::redirect('/account/domains');
        }

        $enable = $request->post('enable', '0') === '1';
        $service = new WhoisPrivacyService();
        $result = $service->toggle($domainId, $enable);

        flash($result['success'] ? 'success' : 'error', $result['success'] ? 'WHOIS privacy updated.' : ($result['error'] ?? 'Failed.'));
        return Response::redirect("/account/domains/{$domainId}/privacy");
    }
}
