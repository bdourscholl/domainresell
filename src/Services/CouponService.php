<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Coupon;

class CouponService
{
    public function validate(string $code, float $orderTotal): array
    {
        $coupon = Coupon::findByCode(strtoupper($code));

        if (!$coupon) {
            return ['valid' => false, 'error' => 'Invalid coupon code'];
        }

        if (!Coupon::isValid($coupon, $orderTotal)) {
            return ['valid' => false, 'error' => 'Coupon is not valid or has expired'];
        }

        $discount = Coupon::calculateDiscount($coupon, $orderTotal);

        return [
            'valid' => true,
            'coupon' => $coupon,
            'discount' => $discount,
            'new_total' => $orderTotal - $discount,
        ];
    }

    public function apply(int $couponId): void
    {
        Coupon::incrementUsage($couponId);
    }
}
