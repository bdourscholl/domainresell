<?php

return [
    'stripe' => [
        'enabled' => false,
        'public_key' => $_ENV['STRIPE_PUBLIC_KEY'] ?? '',
        'secret_key' => $_ENV['STRIPE_SECRET_KEY'] ?? '',
        'webhook_secret' => $_ENV['STRIPE_WEBHOOK_SECRET'] ?? '',
    ],

    'paypal' => [
        'enabled' => false,
        'client_id' => $_ENV['PAYPAL_CLIENT_ID'] ?? '',
        'client_secret' => $_ENV['PAYPAL_CLIENT_SECRET'] ?? '',
        'sandbox' => ($_ENV['PAYPAL_SANDBOX'] ?? 'true') === 'true',
    ],

    'razorpay' => [
        'enabled' => false,
        'key_id' => $_ENV['RAZORPAY_KEY_ID'] ?? '',
        'key_secret' => $_ENV['RAZORPAY_KEY_SECRET'] ?? '',
    ],

    'sslcommerz' => [
        'enabled' => false,
        'store_id' => $_ENV['SSLCOMMERZ_STORE_ID'] ?? '',
        'store_password' => $_ENV['SSLCOMMERZ_STORE_PASSWORD'] ?? '',
        'sandbox' => ($_ENV['SSLCOMMERZ_SANDBOX'] ?? 'true') === 'true',
    ],

    'bkash' => [
        'enabled' => false,
        'app_key' => $_ENV['BKASH_APP_KEY'] ?? '',
        'app_secret' => $_ENV['BKASH_APP_SECRET'] ?? '',
        'username' => $_ENV['BKASH_USERNAME'] ?? '',
        'password' => $_ENV['BKASH_PASSWORD'] ?? '',
        'sandbox' => ($_ENV['BKASH_SANDBOX'] ?? 'true') === 'true',
    ],

    'nagad' => [
        'enabled' => false,
        'merchant_id' => $_ENV['NAGAD_MERCHANT_ID'] ?? '',
        'merchant_key' => $_ENV['NAGAD_MERCHANT_KEY'] ?? '',
        'sandbox' => ($_ENV['NAGAD_SANDBOX'] ?? 'true') === 'true',
    ],

    'manual' => [
        'enabled' => true,
        'instructions' => 'Please transfer the amount to our bank account and submit the transaction reference.',
        'bank_name' => '',
        'account_number' => '',
        'account_name' => '',
        'branch' => '',
    ],
];
