<?php

declare(strict_types=1);

namespace App\Services\Registrar;

class MockRegistrar implements RegistrarInterface
{
    public function checkAvailability(string $domain, string $tld): array
    {
        $hash = crc32($domain . $tld);
        return [
            'available' => ($hash % 3) !== 0,
            'domain' => $domain . $tld,
            'premium' => ($hash % 7) === 0,
        ];
    }

    public function registerDomain(string $domain, string $tld, int $years, array $contact): array
    {
        return [
            'success' => true,
            'domain' => $domain . $tld,
            'order_id' => 'MOCK-' . strtoupper(bin2hex(random_bytes(4))),
        ];
    }

    public function transferDomain(string $domain, string $tld, string $eppCode, array $contact): array
    {
        return [
            'success' => true,
            'domain' => $domain . $tld,
            'transfer_id' => 'MOCK-TR-' . strtoupper(bin2hex(random_bytes(4))),
        ];
    }

    public function renewDomain(string $domain, string $tld, int $years): array
    {
        return ['success' => true, 'domain' => $domain . $tld];
    }

    public function getDomainInfo(string $domain, string $tld): array
    {
        return ['success' => true, 'data' => ['domain' => $domain . $tld, 'status' => 'active']];
    }

    public function getNameservers(string $domain, string $tld): array
    {
        return ['success' => true, 'nameservers' => ['ns1.mock-dns.com', 'ns2.mock-dns.com']];
    }

    public function setNameservers(string $domain, string $tld, array $nameservers): array
    {
        return ['success' => true];
    }

    public function getDnsRecords(string $domain, string $tld): array
    {
        return ['success' => true, 'records' => []];
    }

    public function setDnsRecord(string $domain, string $tld, array $record): array
    {
        return ['success' => true];
    }

    public function deleteDnsRecord(string $domain, string $tld, int $recordId): array
    {
        return ['success' => true];
    }

    public function enableWhoisPrivacy(string $domain, string $tld): array
    {
        return ['success' => true];
    }

    public function disableWhoisPrivacy(string $domain, string $tld): array
    {
        return ['success' => true];
    }

    public function lockDomain(string $domain, string $tld): array
    {
        return ['success' => true];
    }

    public function unlockDomain(string $domain, string $tld): array
    {
        return ['success' => true];
    }

    public function getTransferStatus(string $domain, string $tld): array
    {
        return ['success' => true, 'status' => 'completed'];
    }
}
