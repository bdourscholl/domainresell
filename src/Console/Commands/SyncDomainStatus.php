<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Core\App;
use App\Core\Logger;
use App\Models\Domain;
use App\Models\Transfer;
use App\Services\Registrar\RegistrarFactory;

class SyncDomainStatus
{
    public function handle(array $args = []): int
    {
        echo "Syncing domain statuses...\n";

        $db = App::getInstance()->getDb();

        // Sync pending transfers
        $transfers = Transfer::getPending();
        foreach ($transfers as $transfer) {
            try {
                $registrar = RegistrarFactory::create($transfer['registrar']);
                $status = $registrar->getTransferStatus($transfer['domain_name'], $transfer['tld']);

                if (($status['status'] ?? '') === 'completed') {
                    Transfer::update((int) $transfer['id'], ['status' => 'completed']);
                    echo "Transfer completed: {$transfer['domain_name']}\n";
                }
            } catch (\Exception $e) {
                Logger::error("Sync transfer error: " . $e->getMessage());
            }
        }

        // Sync pending domain registrations
        $pendingDomains = $db->query("SELECT * FROM domains WHERE status = 'pending'");
        foreach ($pendingDomains as $domain) {
            try {
                $registrar = RegistrarFactory::create($domain['registrar']);
                $parts = explode('.', $domain['domain_name'], 2);
                $info = $registrar->getDomainInfo($parts[0], '.' . ($parts[1] ?? ''));

                if ($info['success'] ?? false) {
                    Domain::update((int) $domain['id'], ['status' => 'active']);
                    echo "Domain activated: {$domain['domain_name']}\n";
                }
            } catch (\Exception $e) {
                Logger::error("Sync domain error: " . $e->getMessage());
            }
        }

        echo "Sync complete.\n";
        return 0;
    }
}
