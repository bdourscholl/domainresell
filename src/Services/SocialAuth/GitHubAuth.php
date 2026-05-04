<?php

declare(strict_types=1);

namespace App\Services\SocialAuth;

use App\Core\App;
use GuzzleHttp\Client;

class GitHubAuth implements SocialAuthInterface
{
    private array $config;

    public function __construct()
    {
        $this->config = App::getInstance()->config('socialauth.github');
    }

    public function isEnabled(): bool
    {
        return ($this->config['enabled'] ?? false) && !empty($this->config['client_id']);
    }

    public function getRedirectUrl(): string
    {
        $params = http_build_query([
            'client_id' => $this->config['client_id'],
            'redirect_uri' => $this->config['redirect_uri'],
            'scope' => 'user:email',
        ]);
        return "https://github.com/login/oauth/authorize?{$params}";
    }

    public function handleCallback(string $code): ?array
    {
        $client = new Client(['timeout' => 15]);

        $tokenResponse = $client->post('https://github.com/login/oauth/access_token', [
            'headers' => ['Accept' => 'application/json'],
            'form_params' => [
                'client_id' => $this->config['client_id'],
                'client_secret' => $this->config['client_secret'],
                'code' => $code,
            ],
        ]);

        $tokens = json_decode($tokenResponse->getBody()->getContents(), true);
        $accessToken = $tokens['access_token'] ?? null;

        if (!$accessToken) return null;

        $userResponse = $client->get('https://api.github.com/user', [
            'headers' => ['Authorization' => "Bearer {$accessToken}", 'User-Agent' => 'DomainReseller'],
        ]);
        $userData = json_decode($userResponse->getBody()->getContents(), true);

        $emailResponse = $client->get('https://api.github.com/user/emails', [
            'headers' => ['Authorization' => "Bearer {$accessToken}", 'User-Agent' => 'DomainReseller'],
        ]);
        $emails = json_decode($emailResponse->getBody()->getContents(), true);
        $primaryEmail = '';
        foreach ($emails as $email) {
            if ($email['primary'] ?? false) { $primaryEmail = $email['email']; break; }
        }

        return [
            'provider' => 'github',
            'social_id' => (string) ($userData['id'] ?? ''),
            'name' => $userData['name'] ?? $userData['login'] ?? '',
            'email' => $primaryEmail ?: ($userData['email'] ?? ''),
            'avatar' => $userData['avatar_url'] ?? '',
        ];
    }
}
