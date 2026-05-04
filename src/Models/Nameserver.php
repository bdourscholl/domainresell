<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\App;

class Nameserver
{
    public static function findByDomain(int $domainId): array
    {
        $db = App::getInstance()->getDb();
        return $db->query(
            "SELECT * FROM nameservers WHERE domain_id = ? ORDER BY sort_order ASC",
            [$domainId]
        );
    }

    public static function updateForDomain(int $domainId, array $nameservers): void
    {
        $db = App::getInstance()->getDb();
        $db->delete('nameservers', 'domain_id = ?', [$domainId]);

        foreach ($nameservers as $i => $ns) {
            if (trim($ns)) {
                $db->insert('nameservers', [
                    'domain_id' => $domainId,
                    'nameserver' => trim($ns),
                    'sort_order' => $i,
                ]);
            }
        }
    }
}
