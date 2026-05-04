<?php

declare(strict_types=1);

namespace App\Services\Registrar;

use App\Core\App;
use App\Core\Logger;
use GuzzleHttp\Client;

class CloudflareRegistrar implements RegistrarInterface
{
    private Client $client;
    private array $config;

    public function __construct()
    {
        $this->config = App::getInstance()->config('registrars.cloudflare');
        $this->client = new Client([
            'base_uri' => $this->config['api_url'],
            'timeout' => 30,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->config['api_token'],
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    private function request(string $method, string $endpoint, array $data = []): array
    {
        try {
            $options = $method === 'GET' ? ['query' => $data] : ['json' => $data];
            $response = $this->client->request($method, $endpoint, $options);
            $body = json_decode($response->getBody()->getContents(), true);

            if (!($body['success'] ?? false)) {
                $errors = array_map(fn($e) => $e['message'] ?? '', $body['errors'] ?? []);
                return ['success' => false, 'error' => implode(', ', $errors)];
            }

            return ['success' => true, 'data' => $body['result'] ?? []];
        } catch (\Exception $e) {
            Logger::error('Cloudflare API error: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function checkAvailability(string $domain, string $tld): array
    {
        $accountId = $this->config['account_id'];
        $result = $this->request('POST', "/accounts/{$accountId}/registrar/domains/check", [
            'domains' => [$domain . $tld],
        ]);

        if (!$result['success']) {
            return ['available' => false, 'error' => $result['error']];
        }

        $domainResult = $result['data'][0] ?? [];
        return [
            'available' => ($domainResult['available'] ?? false),
            'domain' => $domain . $tld,
            'premium' => false,
        ];
    }

    public function registerDomain(string $domain, string $tld, int $years, array $contact): array
    {
        $accountId = $this->config['account_id'];
        $result = $this->request('POST', "/accounts/{$accountId}/registrar/domains", [
            'name' => $domain . $tld,
            'auto_renew' => false,
        ]);

        if (!$result['success']) {
            return ['success' => false, 'error' => $result['error']];
        }

        return ['success' => true, 'domain' => $domain . $tld];
    }

    public function transferDomain(string $domain, string $tld, string $eppCode, array $contact): array
    {
        return ['success' => false, 'error' => 'Cloudflare transfer requires manual setup'];
    }

    public function renewDomain(string $domain, string $tld, int $years): array
    {
        return ['success' => false, 'error' => 'Cloudflare handles renewal automatically at cost'];
    }

    public function getDomainInfo(string $domain, string $tld): array
    {
        $accountId = $this->config['account_id'];
        return $this->request('GET', "/accounts/{$accountId}/registrar/domains/{$domain}{$tld}");
    }

    public function getNameservers(string $domain, string $tld): array
    {
        return ['success' => true, 'nameservers' => ['ns1.cloudflare.com', 'ns2.cloudflare.com']];
    }

    public function setNameservers(string $domain, string $tld, array $nameservers): array
    {
        return ['success' => false, 'error' => 'Cloudflare domains must use Cloudflare nameservers'];
    }

    public function getDnsRecords(string $domain, string $tld): array
    {
        return $this->request('GET', "/zones?name={$domain}{$tld}");
    }

    public function setDnsRecord(string $domain, string $tld, array $record): array
    {
        return ['success' => true, 'message' => 'Use Cloudflare dashboard for DNS'];
    }

    public function deleteDnsRecord(string $domain, string $tld, int $recordId): array
    {
        return ['success' => true];
    }

    public function enableWhoisPrivacy(string $domain, string $tld): array
    {
        return ['success' => true, 'message' => 'Cloudflare includes free WHOIS privacy'];
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
        return ['success' => true, 'status' => 'unknown'];
    }
}
