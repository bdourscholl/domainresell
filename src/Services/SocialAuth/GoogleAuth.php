<?php

declare(strict_types=1);

namespace App\Services\SocialAuth;

use App\Core\App;
use GuzzleHttp\Client;

class GoogleAuth implements SocialAuthInterface
{
    private array $config;

    public function __construct()
    {
        $this->config = App::getInstance()->config('socialauth.google');
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
            'response_type' => 'code',
            'scope' => 'openid email profile',
            'access_type' => 'offline',
        ]);
        return "https://accounts.google.com/o/oauth2/v2/auth?{$params}";
    }

    public function handleCallback(string $code): ?array
    {
        $client = new Client(['timeout' => 15]);

        $tokenResponse = $client->post('https://oauth2.googleapis.com/token', [
            'form_params' => [
                'code' => $code,
                'client_id' => $this->config['client_id'],
                'client_secret' => $this->config['client_secret'],
                'redirect_uri' => $this->config['redirect_uri'],
                'grant_type' => 'authorization_code',
            ],
        ]);

        $tokens = json_decode($tokenResponse->getBody()->getContents(), true);
        $accessToken = $tokens['access_token'] ?? null;

        if (!$accessToken) {
            return null;
        }

        $userResponse = $client->get('https://www.googleapis.com/oauth2/v2/userinfo', [
            'headers' => ['Authorization' => "Bearer {$accessToken}"],
        ]);

        $userData = json_decode($userResponse->getBody()->getContents(), true);

        return [
            'provider' => 'google',
            'social_id' => $userData['id'] ?? '',
            'name' => $userData['name'] ?? '',
            'email' => $userData['email'] ?? '',
            'avatar' => $userData['picture'] ?? '',
        ];
    }
}
