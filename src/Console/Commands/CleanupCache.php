<?php

declare(strict_types=1);

namespace App\Console\Commands;

class CleanupCache
{
    public function handle(array $args = []): int
    {
        echo "Cleaning up cache...\n";

        $cacheDir = BASE_PATH . '/storage/cache';
        $count = 0;

        if (is_dir($cacheDir)) {
            foreach (glob($cacheDir . '/*') as $file) {
                if (is_file($file) && (time() - filemtime($file)) > 86400) {
                    unlink($file);
                    $count++;
                }
            }
        }

        // Clean old log files (keep last 30 days)
        $logDir = BASE_PATH . '/storage/logs';
        if (is_dir($logDir)) {
            foreach (glob($logDir . '/*.log') as $file) {
                if ((time() - filemtime($file)) > 2592000) {
                    unlink($file);
                    $count++;
                }
            }
        }

        echo "Cleanup complete. Removed {$count} files.\n";
        return 0;
    }
}
