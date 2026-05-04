<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Models\Domain;
use App\Models\Nameserver;
use App\Models\DnsRecord;

class DomainController
{
    public function search(Request $request): Response
    {
        $query = $request->get('q', '');
        return View::renderWithLayout('domain/search-results', ['query' => $query, 'results' => []], 'main');
    }

    public function details(Request $request): Response
    {
        $domain = $request->param('domain');
        return View::renderWithLayout('domain/details', ['domain_name' => $domain], 'main');
    }

    public function myDomains(Request $request): Response
    {
        $userId = current_user_id();
        $domains = Domain::findByUser($userId);
        return View::renderWithLayout('account/domains', ['domains' => $domains], 'main');
    }
}
