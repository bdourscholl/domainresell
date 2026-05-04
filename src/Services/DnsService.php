<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Domain;
use App\Models\DnsRecord;
use App\Services\Registrar\RegistrarFactory;

class DnsService
{
    public function getRecords(int $domainId): array
    {
        return DnsRecord::findByDomain($domainId);
    }

    public function addRecord(int $domainId, array $data): array
    {
        $domain = Domain::find($domainId);
        if (!$domain) {
            return ['success' => false, 'error' => 'Domain not found'];
        }

        $recordId = DnsRecord::create([
            'domain_id' => $domainId,
            'record_type' => $data['record_type'],
            'name' => $data['name'],
            'value' => $data['value'],
            'ttl' => $data['ttl'] ?? 3600,
            'priority' => $data['priority'] ?? null,
        ]);

        $registrar = RegistrarFactory::create($domain['registrar']);
        $parts = explode('.', $domain['domain_name'], 2);
        $registrar->setDnsRecord($parts[0], '.' . $parts[1], $data);

        return ['success' => true, 'record_id' => $recordId];
    }

    public function deleteRecord(int $recordId, int $domainId): array
    {
        $domain = Domain::find($domainId);
        if (!$domain) {
            return ['success' => false, 'error' => 'Domain not found'];
        }

        DnsRecord::delete($recordId);

        $registrar = RegistrarFactory::create($domain['registrar']);
        $parts = explode('.', $domain['domain_name'], 2);
        $registrar->deleteDnsRecord($parts[0], '.' . $parts[1], $recordId);

        return ['success' => true];
    }
}
