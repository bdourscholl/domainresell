<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Core\Logger;
use App\Models\Domain;
use App\Services\EmailService;
use App\Services\Notification\NotificationManager;

class ExpiryReminder
{
    public function handle(array $args = []): int
    {
        echo "Sending expiry reminders...\n";

        $reminderDays = [30, 15, 7, 3, 1];
        $emailService = new EmailService();
        $notifier = new NotificationManager();

        foreach ($reminderDays as $days) {
            $domains = Domain::getExpiring($days);

            foreach ($domains as $domain) {
                $daysLeft = (int) ((strtotime($domain['expiry_date']) - time()) / 86400);

                if ($daysLeft !== $days) {
                    continue;
                }

                try {
                    $emailService->sendTemplate('domain_expiring', $domain['user_email'], [
                        'name' => $domain['user_name'],
                        'domain' => $domain['domain_name'],
                        'expiry_date' => $domain['expiry_date'],
                        'days' => $days,
                    ]);

                    $notifier->notifyDomainExpiring([
                        'domain_name' => $domain['domain_name'],
                        'expiry_date' => $domain['expiry_date'],
                        'user_name' => $domain['user_name'],
                    ]);

                    echo "Reminder sent for {$domain['domain_name']} ({$days} days)\n";
                } catch (\Exception $e) {
                    Logger::error("Expiry reminder error: " . $e->getMessage());
                }
            }
        }

        echo "Expiry reminders complete.\n";
        return 0;
    }
}
