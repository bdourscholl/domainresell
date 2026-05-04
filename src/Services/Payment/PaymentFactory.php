<?php

declare(strict_types=1);

namespace App\Services\Payment;

use App\Core\App;

class PaymentFactory
{
    public static function create(string $gateway): PaymentInterface
    {
        return match ($gateway) {
            'stripe' => new StripePayment(),
            'paypal' => new PayPalPayment(),
            'razorpay' => new RazorpayPayment(),
            'sslcommerz' => new SslcommerzPayment(),
            'bkash' => new BkashPayment(),
            'nagad' => new NagadPayment(),
            'manual' => new ManualPayment(),
            default => throw new \InvalidArgumentException("Unknown payment gateway: {$gateway}"),
        };
    }

    public static function getEnabled(): array
    {
        $config = App::getInstance()->config('payments');
        $enabled = [];

        foreach ($config as $name => $settings) {
            if ($settings['enabled'] ?? false) {
                $enabled[$name] = $settings;
            }
        }

        return $enabled;
    }
}
