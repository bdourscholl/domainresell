<?php

declare(strict_types=1);

namespace App\Services\SocialAuth;

use App\Core\App;
use GuzzleHttp\Client;

class FacebookAuth implements SocialAuthInterface
{
    private array $config;

    public function __construct()
    {
        $this->config = App::getInstance()->config('socialauth.facebook');
    }

    public function isEnabled(): bool
    {
        return ($this->config['enabled'] ?? false) && !empty($this->config['app_id']);
    }

    public function getRedirectUrl(): string
    {
        $params = http_build_query([
            'client_id' => $this->config['app_id'],
            'redirect_uri' => $this->config['redirect_uri'],
            'scope' => 'email,public_profile',
            'response_type' => 'code',
        ]);
        return "https://www.facebook.com/v18.0/dialog/oauth?{$params}";
    }

    public function handleCallback(string $code): ?array
    {
        $client = new Client(['timeout' => 15]);

        $tokenResponse = $client->get('https://graph.facebook.com/v18.0/oauth/access_token', [
            'query' => [
                'client_id' => $this->config['app_id'],
                'client_secret' => $this->config['app_secret'],
                'redirect_uri' => $this->config['redirect_uri'],
                'code' => $code,
            ],
        ]);

        $tokens = json_decode($tokenResponse->getBody()->getContents(), true);
        $accessToken = $tokens['access_token'] ?? null;

        if (!$accessToken) return null;

        $userResponse = $client->get('https://graph.facebook.com/v18.0/me', [
            'query' => ['fields' => 'id,name,email,picture.type(large)', 'access_token' => $accessToken],
        ]);

        $userData = json_decode($userResponse->getBody()->getContents(), true);

        return [
            'provider' => 'facebook',
            'social_id' => $userData['id'] ?? '',
            'name' => $userData['name'] ?? '',
            'email' => $userData['email'] ?? '',
            'avatar' => $userData['picture']['data']['url'] ?? '',
        ];
    }
}
