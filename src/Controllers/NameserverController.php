<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Models\Domain;
use App\Models\Nameserver;
use App\Services\NameserverService;

class NameserverController
{
    public function index(Request $request): Response
    {
        $domainId = (int) $request->param('id');
        $domain = Domain::find($domainId);

        if (!$domain || (int) $domain['user_id'] !== current_user_id()) {
            flash('error', 'Domain not found.');
            return Response::redirect('/account/domains');
        }

        $nameservers = Nameserver::findByDomain($domainId);
        return View::renderWithLayout('account/nameservers', ['domain' => $domain, 'nameservers' => $nameservers], 'main');
    }

    public function update(Request $request): Response
    {
        $domainId = (int) $request->param('id');
        $domain = Domain::find($domainId);

        if (!$domain || (int) $domain['user_id'] !== current_user_id()) {
            flash('error', 'Domain not found.');
            return Response::redirect('/account/domains');
        }

        $nameservers = array_filter([
            $request->post('ns1', ''),
            $request->post('ns2', ''),
            $request->post('ns3', ''),
            $request->post('ns4', ''),
        ]);

        $service = new NameserverService();
        $result = $service->update($domainId, $nameservers);

        flash($result['success'] ? 'success' : 'error', $result['success'] ? 'Nameservers updated.' : ($result['error'] ?? 'Failed.'));
        return Response::redirect("/account/domains/{$domainId}/nameservers");
    }
}
