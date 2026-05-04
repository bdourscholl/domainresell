<?php

return [
    'host' => $_ENV['MAIL_HOST'] ?? 'smtp.gmail.com',
    'port' => (int) ($_ENV['MAIL_PORT'] ?? 587),
    'username' => $_ENV['MAIL_USERNAME'] ?? '',
    'password' => $_ENV['MAIL_PASSWORD'] ?? '',
    'from_address' => $_ENV['MAIL_FROM_ADDRESS'] ?? 'noreply@yourdomain.com',
    'from_name' => $_ENV['MAIL_FROM_NAME'] ?? ($_ENV['APP_NAME'] ?? 'Domain Reseller'),
    'encryption' => $_ENV['MAIL_ENCRYPTION'] ?? 'tls',
];
