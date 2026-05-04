<?php

return [
    'name' => $_ENV['APP_NAME'] ?? 'Domain Reseller',
    'url' => $_ENV['APP_URL'] ?? 'http://localhost:8000',
    'env' => $_ENV['APP_ENV'] ?? 'local',
    'debug' => ($_ENV['APP_DEBUG'] ?? 'true') === 'true',
    'timezone' => $_ENV['APP_TIMEZONE'] ?? 'Asia/Dhaka',
    'locale' => $_ENV['APP_LOCALE'] ?? 'en',
    'supported_locales' => ['en', 'bn'],
    'currency' => $_ENV['APP_CURRENCY'] ?? 'BDT',
    'currency_symbol' => $_ENV['APP_CURRENCY_SYMBOL'] ?? '৳',
];
