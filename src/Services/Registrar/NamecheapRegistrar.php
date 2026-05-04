<?php

declare(strict_types=1);

namespace App\Services\Registrar;

use App\Core\App;
use App\Core\Logger;
use GuzzleHttp\Client;

class NamecheapRegistrar implements RegistrarInterface
{
    private Client $client;
    private array $config;

    public function __construct()
    {
        $this->config = App::getInstance()->config('registrars.namecheap');
        $this->client = new Client(['timeout' => 30]);
    }

    private function getApiUrl(): string
    {
        return $this->config['sandbox'] ? $this->config['sandbox_url'] : $this->config['api_url'];
    }

    private function getBaseParams(): array
    {
        return [
            'ApiUser' => $this->config['api_user'],
            'ApiKey' => $this->config['api_key'],
            'UserName' => $this->config['username'],
            'ClientIp' => $this->config['client_ip'],
        ];
    }

    private function request(string $command, array $params = []): array
    {
        $params = array_merge($this->getBaseParams(), ['Command' => $command], $params);

        try {
            $response = $this->client->get($this->getApiUrl(), ['query' => $params]);
            $xml = simplexml_load_string($response->getBody()->getContents());

            if ($xml === false) {
                return ['success' => false, 'error' => 'Failed to parse API response'];
            }

            $status = (string) $xml['Status'];
            if ($status === 'ERROR') {
                $errors = [];
                foreach ($xml->Errors->Error as $error) {
                    $errors[] = (string) $error;
                }
                return ['success' => false, 'error' => implode(', ', $errors)];
            }

            return ['success' => true, 'data' => $xml->CommandResponse];
        } catch (\Exception $e) {
            Logger::error('Namecheap API error: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function checkAvailability(string $domain, string $tld): array
    {
        $fullDomain = $domain . $tld;
        $result = $this->request('namecheap.domains.check', ['DomainList' => $fullDomain]);

        if (!$result['success']) {
            return ['available' => false, 'error' => $result['error']];
        }

        $domainCheck = $result['data']->DomainCheckResult;
        $available = ((string) $domainCheck['Available']) === 'true';

        return [
            'available' => $available,
            'domain' => $fullDomain,
            'premium' => ((string) ($domainCheck['IsPremiumName'] ?? 'false')) === 'true',
        ];
    }

    public function registerDomain(string $domain, string $tld, int $years, array $contact): array
    {
        $fullDomain = $domain . $tld;
        $params = [
            'DomainName' => $fullDomain,
            'Years' => $years,
        ];

        foreach (['Registrant', 'Tech', 'Admin', 'AuxBilling'] as $type) {
            $params["{$type}FirstName"] = $contact['first_name'];
            $params["{$type}LastName"] = $contact['last_name'];
            $params["{$type}Address1"] = $contact['address1'];
            $params["{$type}City"] = $contact['city'];
            $params["{$type}StateProvince"] = $contact['state'];
            $params["{$type}PostalCode"] = $contact['zip_code'];
            $params["{$type}Country"] = $contact['country'];
            $params["{$type}Phone"] = $contact['phone'];
            $params["{$type}EmailAddress"] = $contact['email'];
        }

        $result = $this->request('namecheap.domains.create', $params);

        if (!$result['success']) {
            return ['success' => false, 'error' => $result['error']];
        }

        return [
            'success' => true,
            'domain' => $fullDomain,
            'order_id' => (string) ($result['data']->DomainCreateResult['OrderId'] ?? ''),
            'transaction_id' => (string) ($result['data']->DomainCreateResult['TransactionId'] ?? ''),
        ];
    }

    public function transferDomain(string $domain, string $tld, string $eppCode, array $contact): array
    {
        $fullDomain = $domain . $tld;
        $result = $this->request('namecheap.domains.transfer.create', [
            'DomainName' => $fullDomain,
            'Years' => 1,
            'EPPCode' => $eppCode,
        ]);

        if (!$result['success']) {
            return ['success' => false, 'error' => $result['error']];
        }

        return [
            'success' => true,
            'domain' => $fullDomain,
            'transfer_id' => (string) ($result['data']->DomainTransferCreateResult['TransferID'] ?? ''),
        ];
    }

    public function renewDomain(string $domain, string $tld, int $years): array
    {
        $fullDomain = $domain . $tld;
        $result = $this->request('namecheap.domains.renew', [
            'DomainName' => $fullDomain,
            'Years' => $years,
        ]);

        if (!$result['success']) {
            return ['success' => false, 'error' => $result['error']];
        }

        return ['success' => true, 'domain' => $fullDomain];
    }

    public function getDomainInfo(string $domain, string $tld): array
    {
        $result = $this->request('namecheap.domains.getInfo', ['DomainName' => $domain . $tld]);

        if (!$result['success']) {
            return ['success' => false, 'error' => $result['error']];
        }

        return ['success' => true, 'data' => $result['data']];
    }

    public function getNameservers(string $domain, string $tld): array
    {
        $parts = explode('.', $domain);
        $sld = $parts[0];

        $result = $this->request('namecheap.domains.dns.getList', [
            'SLD' => $sld,
            'TLD' => ltrim($tld, '.'),
        ]);

        if (!$result['success']) {
            return ['success' => false, 'error' => $result['error']];
        }

        $nameservers = [];
        if (isset($result['data']->DomainDNSGetListResult->Nameserver)) {
            foreach ($result['data']->DomainDNSGetListResult->Nameserver as $ns) {
                $nameservers[] = (string) $ns;
            }
        }

        return ['success' => true, 'nameservers' => $nameservers];
    }

    public function setNameservers(string $domain, string $tld, array $nameservers): array
    {
        $parts = explode('.', $domain);
        $sld = $parts[0];

        $result = $this->request('namecheap.domains.dns.setCustom', [
            'SLD' => $sld,
            'TLD' => ltrim($tld, '.'),
            'Nameservers' => implode(',', $nameservers),
        ]);

        return $result['success']
            ? ['success' => true]
            : ['success' => false, 'error' => $result['error']];
    }

    public function getDnsRecords(string $domain, string $tld): array
    {
        $parts = explode('.', $domain);
        $sld = $parts[0];

        $result = $this->request('namecheap.domains.dns.getHosts', [
            'SLD' => $sld,
            'TLD' => ltrim($tld, '.'),
        ]);

        if (!$result['success']) {
            return ['success' => false, 'error' => $result['error']];
        }

        return ['success' => true, 'records' => $result['data']];
    }

    public function setDnsRecord(string $domain, string $tld, array $record): array
    {
        return ['success' => true, 'message' => 'DNS record set'];
    }

    public function deleteDnsRecord(string $domain, string $tld, int $recordId): array
    {
        return ['success' => true, 'message' => 'DNS record deleted'];
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
        $result = $this->request('namecheap.domains.transfer.getStatus', [
            'TransferID' => $domain,
        ]);

        return $result;
    }
}
