<?php

return [
    'default' => $_ENV['DEFAULT_REGISTRAR'] ?? 'namecheap',

    'namecheap' => [
        'enabled' => true,
        'api_user' => $_ENV['NAMECHEAP_API_USER'] ?? '',
        'api_key' => $_ENV['NAMECHEAP_API_KEY'] ?? '',
        'username' => $_ENV['NAMECHEAP_USERNAME'] ?? '',
        'client_ip' => $_ENV['NAMECHEAP_CLIENT_IP'] ?? '',
        'sandbox' => ($_ENV['NAMECHEAP_SANDBOX'] ?? 'true') === 'true',
        'api_url' => 'https://api.namecheap.com/xml.response',
        'sandbox_url' => 'https://api.sandbox.namecheap.com/xml.response',
    ],

    'spaceship' => [
        'enabled' => false,
        'api_key' => $_ENV['SPACESHIP_API_KEY'] ?? '',
        'api_secret' => $_ENV['SPACESHIP_API_SECRET'] ?? '',
        'sandbox' => ($_ENV['SPACESHIP_SANDBOX'] ?? 'true') === 'true',
        'api_url' => 'https://spaceship.dev/api/v1',
        'sandbox_url' => 'https://sandbox.spaceship.dev/api/v1',
    ],

    'cloudflare' => [
        'enabled' => false,
        'api_token' => $_ENV['CLOUDFLARE_API_TOKEN'] ?? '',
        'account_id' => $_ENV['CLOUDFLARE_ACCOUNT_ID'] ?? '',
        'api_url' => 'https://api.cloudflare.com/client/v4',
    ],
];
