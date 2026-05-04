<?php

declare(strict_types=1);

namespace App\Services\Registrar;

interface RegistrarInterface
{
    public function checkAvailability(string $domain, string $tld): array;
    public function registerDomain(string $domain, string $tld, int $years, array $contact): array;
    public function transferDomain(string $domain, string $tld, string $eppCode, array $contact): array;
    public function renewDomain(string $domain, string $tld, int $years): array;
    public function getDomainInfo(string $domain, string $tld): array;
    public function getNameservers(string $domain, string $tld): array;
    public function setNameservers(string $domain, string $tld, array $nameservers): array;
    public function getDnsRecords(string $domain, string $tld): array;
    public function setDnsRecord(string $domain, string $tld, array $record): array;
    public function deleteDnsRecord(string $domain, string $tld, int $recordId): array;
    public function enableWhoisPrivacy(string $domain, string $tld): array;
    public function disableWhoisPrivacy(string $domain, string $tld): array;
    public function lockDomain(string $domain, string $tld): array;
    public function unlockDomain(string $domain, string $tld): array;
    public function getTransferStatus(string $domain, string $tld): array;
}
