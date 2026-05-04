<?php

declare(strict_types=1);

namespace App\Services\Registrar;

use App\Core\App;
use App\Core\Logger;
use GuzzleHttp\Client;

class SpaceshipRegistrar implements RegistrarInterface
{
    private Client $client;
    private array $config;

    public function __construct()
    {
        $this->config = App::getInstance()->config('registrars.spaceship');
        $baseUrl = $this->config['sandbox'] ? $this->config['sandbox_url'] : $this->config['api_url'];

        $this->client = new Client([
            'base_uri' => $baseUrl,
            'timeout' => 30,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->config['api_key'],
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ]);
    }

    private function request(string $method, string $endpoint, array $data = []): array
    {
        try {
            $options = $method === 'GET' ? ['query' => $data] : ['json' => $data];
            $response = $this->client->request($method, $endpoint, $options);
            $body = json_decode($response->getBody()->getContents(), true);
            return ['success' => true, 'data' => $body];
        } catch (\Exception $e) {
            Logger::error('Spaceship API error: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function checkAvailability(string $domain, string $tld): array
    {
        $result = $this->request('GET', '/domains/check', ['domain' => $domain . $tld]);
        if (!$result['success']) {
            return ['available' => false, 'error' => $result['error']];
        }
        return [
            'available' => $result['data']['available'] ?? false,
            'domain' => $domain . $tld,
            'premium' => $result['data']['premium'] ?? false,
        ];
    }

    public function registerDomain(string $domain, string $tld, int $years, array $contact): array
    {
        $result = $this->request('POST', '/domains/register', [
            'domain' => $domain . $tld,
            'years' => $years,
            'contacts' => $contact,
        ]);
        if (!$result['success']) {
            return ['success' => false, 'error' => $result['error']];
        }
        return ['success' => true, 'domain' => $domain . $tld, 'order_id' => $result['data']['order_id'] ?? ''];
    }

    public function transferDomain(string $domain, string $tld, string $eppCode, array $contact): array
    {
        $result = $this->request('POST', '/domains/transfer', [
            'domain' => $domain . $tld,
            'epp_code' => $eppCode,
        ]);
        if (!$result['success']) {
            return ['success' => false, 'error' => $result['error']];
        }
        return ['success' => true, 'domain' => $domain . $tld, 'transfer_id' => $result['data']['transfer_id'] ?? ''];
    }

    public function renewDomain(string $domain, string $tld, int $years): array
    {
        $result = $this->request('POST', '/domains/renew', ['domain' => $domain . $tld, 'years' => $years]);
        return $result['success'] ? ['success' => true, 'domain' => $domain . $tld] : ['success' => false, 'error' => $result['error']];
    }

    public function getDomainInfo(string $domain, string $tld): array
    {
        return $this->request('GET', '/domains/' . $domain . $tld);
    }

    public function getNameservers(string $domain, string $tld): array
    {
        $result = $this->request('GET', '/domains/' . $domain . $tld . '/nameservers');
        return $result['success'] ? ['success' => true, 'nameservers' => $result['data']['nameservers'] ?? []] : $result;
    }

    public function setNameservers(string $domain, string $tld, array $nameservers): array
    {
        return $this->request('PUT', '/domains/' . $domain . $tld . '/nameservers', ['nameservers' => $nameservers]);
    }

    public function getDnsRecords(string $domain, string $tld): array
    {
        return $this->request('GET', '/domains/' . $domain . $tld . '/dns');
    }

    public function setDnsRecord(string $domain, string $tld, array $record): array
    {
        return $this->request('POST', '/domains/' . $domain . $tld . '/dns', $record);
    }

    public function deleteDnsRecord(string $domain, string $tld, int $recordId): array
    {
        return $this->request('DELETE', '/domains/' . $domain . $tld . '/dns/' . $recordId);
    }

    public function enableWhoisPrivacy(string $domain, string $tld): array
    {
        return $this->request('POST', '/domains/' . $domain . $tld . '/privacy', ['enabled' => true]);
    }

    public function disableWhoisPrivacy(string $domain, string $tld): array
    {
        return $this->request('POST', '/domains/' . $domain . $tld . '/privacy', ['enabled' => false]);
    }

    public function lockDomain(string $domain, string $tld): array
    {
        return $this->request('POST', '/domains/' . $domain . $tld . '/lock');
    }

    public function unlockDomain(string $domain, string $tld): array
    {
        return $this->request('DELETE', '/domains/' . $domain . $tld . '/lock');
    }

    public function getTransferStatus(string $domain, string $tld): array
    {
        return $this->request('GET', '/domains/' . $domain . $tld . '/transfer/status');
    }
}
