<?php

declare(strict_types=1);

namespace App\Services\Payment;

interface PaymentInterface
{
    public function getName(): string;
    public function createPayment(float $amount, string $currency, array $orderData): array;
    public function verifyPayment(array $data): array;
    public function getPaymentUrl(array $paymentData): string;
    public function handleWebhook(array $payload): array;
    public function refund(string $transactionId, float $amount): array;
}
