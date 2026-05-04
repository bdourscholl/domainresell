<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Domain;
use App\Models\Nameserver;
use App\Services\Registrar\RegistrarFactory;

class NameserverService
{
    public function update(int $domainId, array $nameservers): array
    {
        $domain = Domain::find($domainId);
        if (!$domain) return ['success' => false, 'error' => 'Domain not found'];

        $nameservers = array_filter($nameservers, fn($ns) => !empty(trim($ns)));
        if (count($nameservers) < 2) return ['success' => false, 'error' => 'At least 2 nameservers required'];

        $parts = explode('.', $domain['domain_name'], 2);
        $registrar = RegistrarFactory::create($domain['registrar']);
        $result = $registrar->setNameservers($parts[0], '.' . $parts[1], $nameservers);

        if ($result['success'] ?? false) {
            Nameserver::updateForDomain($domainId, $nameservers);
        }

        return $result;
    }
}
