<?php

declare(strict_types=1);

namespace App\Console;

use App\Console\Commands\SyncDomainStatus;
use App\Console\Commands\ExpiryReminder;
use App\Console\Commands\SyncRegistrars;
use App\Console\Commands\CleanupCache;

class Kernel
{
    private array $commands = [
        'sync:domains' => SyncDomainStatus::class,
        'notify:expiry' => ExpiryReminder::class,
        'sync:registrars' => SyncRegistrars::class,
        'cache:cleanup' => CleanupCache::class,
    ];

    public function run(string $command, array $args = []): int
    {
        if (!isset($this->commands[$command])) {
            echo "Unknown command: {$command}\n";
            echo "Available commands:\n";
            foreach (array_keys($this->commands) as $cmd) {
                echo "  {$cmd}\n";
            }
            return 1;
        }

        $class = $this->commands[$command];
        $instance = new $class();
        return $instance->handle($args);
    }
}
