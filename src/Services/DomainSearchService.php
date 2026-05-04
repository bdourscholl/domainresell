<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\TldPricing;
use App\Services\Registrar\RegistrarFactory;

class DomainSearchService
{
    public function search(string $query): array
    {
        $query = strtolower(trim($query));
        $query = preg_replace('/[^a-z0-9\-.]/', '', $query);

        $parts = explode('.', $query, 2);
        $sld = $parts[0];
        $requestedTld = isset($parts[1]) ? '.' . $parts[1] : null;

        $results = [];
        $tlds = TldPricing::getAll(true);

        foreach ($tlds as $tld) {
            if ($requestedTld && $tld['tld'] !== $requestedTld) {
                continue;
            }

            $registrar = RegistrarFactory::create($tld['registrar']);
            $check = $registrar->checkAvailability($sld, $tld['tld']);

            $results[] = [
                'domain' => $sld . $tld['tld'],
                'tld' => $tld['tld'],
                'available' => $check['available'],
                'premium' => $check['premium'] ?? false,
                'register_price' => $tld['register_price'],
                'renew_price' => $tld['renew_price'],
                'transfer_price' => $tld['transfer_price'],
                'currency' => $tld['currency'],
            ];

            if ($requestedTld) {
                break;
            }
        }

        usort($results, function ($a, $b) {
            if ($a['available'] !== $b['available']) {
                return $b['available'] <=> $a['available'];
            }
            return $a['register_price'] <=> $b['register_price'];
        });

        return $results;
    }
}
