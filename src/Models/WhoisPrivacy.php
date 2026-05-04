<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\App;

class WhoisPrivacy
{
    public static function findByDomain(int $domainId): ?array
    {
        $db = App::getInstance()->getDb();
        return $db->queryOne("SELECT * FROM whois_privacy WHERE domain_id = ?", [$domainId]);
    }

    public static function toggle(int $domainId, bool $enabled): void
    {
        $db = App::getInstance()->getDb();
        $existing = self::findByDomain($domainId);

        if ($existing) {
            $db->update('whois_privacy', ['enabled' => $enabled ? 1 : 0], 'domain_id = ?', [$domainId]);
        } else {
            $db->insert('whois_privacy', ['domain_id' => $domainId, 'enabled' => $enabled ? 1 : 0]);
        }
    }
}
