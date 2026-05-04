<?php

declare(strict_types=1);

namespace App\Services\SocialAuth;

class SocialAuthFactory
{
    public static function create(string $provider): SocialAuthInterface
    {
        return match ($provider) {
            'google' => new GoogleAuth(),
            'facebook' => new FacebookAuth(),
            'github' => new GitHubAuth(),
            default => throw new \InvalidArgumentException("Unknown provider: {$provider}"),
        };
    }
}
