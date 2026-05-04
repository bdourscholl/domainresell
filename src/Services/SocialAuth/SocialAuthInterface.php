<?php

declare(strict_types=1);

namespace App\Services\SocialAuth;

interface SocialAuthInterface
{
    public function getRedirectUrl(): string;
    public function handleCallback(string $code): ?array;
    public function isEnabled(): bool;
}
