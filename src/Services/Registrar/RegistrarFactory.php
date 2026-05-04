<?php

declare(strict_types=1);

namespace App\Services\Registrar;

use App\Core\App;

class RegistrarFactory
{
    public static function create(?string $registrar = null): RegistrarInterface
    {
        $registrar = $registrar ?? setting('default_registrar', 'namecheap');

        return match ($registrar) {
            'namecheap' => new NamecheapRegistrar(),
            'spaceship' => new SpaceshipRegistrar(),
            'cloudflare' => new CloudflareRegistrar(),
            'mock' => new MockRegistrar(),
            default => new NamecheapRegistrar(),
        };
    }

    public static function getEnabled(): array
    {
        $config = App::getInstance()->config('registrars');
        $enabled = [];

        foreach (['namecheap', 'spaceship', 'cloudflare'] as $name) {
            if (($config[$name]['enabled'] ?? false)) {
                $enabled[] = $name;
            }
        }

        return $enabled;
    }
}
