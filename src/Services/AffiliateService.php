<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Affiliate;
use App\Models\AffiliateTransaction;
use App\Models\User;
use App\Models\Wallet;

class AffiliateService
{
    public function getOrCreate(int $userId): array
    {
        $affiliate = Affiliate::findByUser($userId);
        if (!$affiliate) {
            $id = Affiliate::create([
                'user_id' => $userId,
                'status' => 'active',
                'commission_rate' => (float) setting('affiliate_commission_rate', '10'),
            ]);
            $affiliate = Affiliate::findByUser($userId);
        }
        return $affiliate;
    }

    public function processReferralCommission(int $orderId, int $referredUserId, float $orderTotal): void
    {
        $user = User::find($referredUserId);
        if (!$user || !$user['referred_by']) return;

        $referrer = User::find((int) $user['referred_by']);
        if (!$referrer) return;

        $affiliate = Affiliate::findByUser((int) $referrer['id']);
        if (!$affiliate || $affiliate['status'] !== 'active') return;

        $commission = $orderTotal * ((float) $affiliate['commission_rate'] / 100);

        AffiliateTransaction::create([
            'affiliate_id' => $affiliate['id'],
            'order_id' => $orderId,
            'referred_user_id' => $referredUserId,
            'amount' => $commission,
            'status' => 'pending',
            'description' => "Commission for order #{$orderId}",
        ]);

        Affiliate::update((int) $affiliate['id'], [
            'total_earnings' => (float) $affiliate['total_earnings'] + $commission,
            'total_referrals' => (int) $affiliate['total_referrals'] + 1,
        ]);
    }
}
