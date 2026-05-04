<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Domain;
use App\Models\WhoisPrivacy;
use App\Services\Registrar\RegistrarFactory;

class WhoisPrivacyService
{
    public function toggle(int $domainId, bool $enable): array
    {
        $domain = Domain::find($domainId);
        if (!$domain) return ['success' => false, 'error' => 'Domain not found'];

        $parts = explode('.', $domain['domain_name'], 2);
        $registrar = RegistrarFactory::create($domain['registrar']);

        $result = $enable
            ? $registrar->enableWhoisPrivacy($parts[0], '.' . $parts[1])
            : $registrar->disableWhoisPrivacy($parts[0], '.' . $parts[1]);

        if ($result['success'] ?? false) {
            WhoisPrivacy::toggle($domainId, $enable);
            Domain::update($domainId, ['whois_privacy' => $enable ? 1 : 0]);
        }

        return $result;
    }
}
