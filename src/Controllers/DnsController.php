<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\Validator;
use App\Core\View;
use App\Models\Domain;
use App\Services\DnsService;

class DnsController
{
    public function index(Request $request): Response
    {
        $domainId = (int) $request->param('id');
        $domain = Domain::find($domainId);

        if (!$domain || (int) $domain['user_id'] !== current_user_id()) {
            flash('error', 'Domain not found.');
            return Response::redirect('/account/domains');
        }

        $dnsService = new DnsService();
        $records = $dnsService->getRecords($domainId);

        return View::renderWithLayout('account/dns-records', ['domain' => $domain, 'records' => $records], 'main');
    }

    public function add(Request $request): Response
    {
        $domainId = (int) $request->param('id');
        $domain = Domain::find($domainId);

        if (!$domain || (int) $domain['user_id'] !== current_user_id()) {
            return Response::redirect('/account/domains');
        }

        $validator = new Validator($request->all());
        $validator->validate([
            'record_type' => 'required|in:A,AAAA,CNAME,MX,TXT,NS,SRV',
            'name' => 'required',
            'value' => 'required',
        ]);

        if ($validator->getErrors()) {
            flash('error', $validator->getFirstError());
            return Response::redirect("/account/domains/{$domainId}/dns");
        }

        $dnsService = new DnsService();
        $dnsService->addRecord($domainId, $request->all());

        flash('success', 'DNS record added.');
        return Response::redirect("/account/domains/{$domainId}/dns");
    }

    public function delete(Request $request): Response
    {
        $domainId = (int) $request->param('id');
        $recordId = (int) $request->param('recordId');

        $domain = Domain::find($domainId);
        if (!$domain || (int) $domain['user_id'] !== current_user_id()) {
            return Response::redirect('/account/domains');
        }

        $dnsService = new DnsService();
        $dnsService->deleteRecord($recordId, $domainId);

        flash('success', 'DNS record deleted.');
        return Response::redirect("/account/domains/{$domainId}/dns");
    }
}
