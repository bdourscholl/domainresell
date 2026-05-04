<?php

return [
    'telegram' => [
        'enabled' => false,
        'bot_token' => $_ENV['TELEGRAM_BOT_TOKEN'] ?? '',
        'chat_id' => $_ENV['TELEGRAM_CHAT_ID'] ?? '',
        'events' => [
            'new_order' => true,
            'new_ticket' => true,
            'new_verification' => true,
            'payment_received' => true,
            'domain_expiring' => true,
        ],
    ],

    'whatsapp' => [
        'enabled' => false,
        'api_url' => $_ENV['WHATSAPP_API_URL'] ?? '',
        'api_token' => $_ENV['WHATSAPP_API_TOKEN'] ?? '',
        'phone_number_id' => $_ENV['WHATSAPP_PHONE_NUMBER_ID'] ?? '',
        'admin_phone' => $_ENV['WHATSAPP_ADMIN_PHONE'] ?? '',
        'events' => [
            'new_order' => true,
            'new_ticket' => true,
            'new_verification' => true,
            'payment_received' => true,
            'domain_expiring' => true,
        ],
    ],

    'email' => [
        'enabled' => true,
        'events' => [
            'new_order' => true,
            'order_completed' => true,
            'payment_received' => true,
            'ticket_reply' => true,
            'domain_expiring' => true,
            'verification_approved' => true,
            'verification_rejected' => true,
            'welcome' => true,
            'password_reset' => true,
        ],
    ],
];
