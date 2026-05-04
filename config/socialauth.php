<?php

return [
    'google' => [
        'enabled' => false,
        'client_id' => $_ENV['GOOGLE_CLIENT_ID'] ?? '',
        'client_secret' => $_ENV['GOOGLE_CLIENT_SECRET'] ?? '',
        'redirect_uri' => $_ENV['GOOGLE_REDIRECT_URI'] ?? '',
    ],

    'facebook' => [
        'enabled' => false,
        'app_id' => $_ENV['FACEBOOK_APP_ID'] ?? '',
        'app_secret' => $_ENV['FACEBOOK_APP_SECRET'] ?? '',
        'redirect_uri' => $_ENV['FACEBOOK_REDIRECT_URI'] ?? '',
    ],

    'github' => [
        'enabled' => false,
        'client_id' => $_ENV['GITHUB_CLIENT_ID'] ?? '',
        'client_secret' => $_ENV['GITHUB_CLIENT_SECRET'] ?? '',
        'redirect_uri' => $_ENV['GITHUB_REDIRECT_URI'] ?? '',
    ],
];
