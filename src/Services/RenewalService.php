<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Domain;
use App\Services\Registrar\RegistrarFactory;

class RenewalService
{
    public function renew(int $domainId, int $years = 1): array
    {
        $domain = Domain::find($domainId);
        if (!$domain) return ['success' => false, 'error' => 'Domain not found'];

        $parts = explode('.', $domain['domain_name'], 2);
        $registrar = RegistrarFactory::create($domain['registrar']);
        $result = $registrar->renewDomain($parts[0], '.' . $parts[1], $years);

        if ($result['success'] ?? false) {
            $newExpiry = date('Y-m-d', strtotime($domain['expiry_date'] . " +{$years} years"));
            Domain::update($domainId, ['expiry_date' => $newExpiry]);
        }

        return $result;
    }
}
