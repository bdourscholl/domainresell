<?php

declare(strict_types=1);

namespace App\Helpers;

class CurrencyHelper
{
    private static array $currencies = [
        'BDT' => ['symbol' => '৳', 'name' => 'Bangladeshi Taka', 'decimals' => 2],
        'USD' => ['symbol' => '$', 'name' => 'US Dollar', 'decimals' => 2],
        'EUR' => ['symbol' => '€', 'name' => 'Euro', 'decimals' => 2],
        'GBP' => ['symbol' => '£', 'name' => 'British Pound', 'decimals' => 2],
        'INR' => ['symbol' => '₹', 'name' => 'Indian Rupee', 'decimals' => 2],
    ];

    public static function format(float $amount, string $currency = 'BDT'): string
    {
        $config = self::$currencies[$currency] ?? self::$currencies['BDT'];
        return $config['symbol'] . number_format($amount, $config['decimals']);
    }

    public static function getSymbol(string $currency = 'BDT'): string
    {
        return self::$currencies[$currency]['symbol'] ?? '৳';
    }

    public static function getAvailableCurrencies(): array
    {
        return self::$currencies;
    }
}
