<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\App;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;

class MaintenanceMiddleware
{
    public function handle(Request $request): ?Response
    {
        $uri = $request->getUri();

        // Allow admin access during maintenance
        if (str_starts_with($uri, '/admin') || $uri === '/login' || str_starts_with($uri, '/webhook/')) {
            return null;
        }

        try {
            $app = App::getInstance();
            $db = $app->getDb();
            $setting = $db->queryOne(
                "SELECT setting_value FROM site_settings WHERE setting_key = 'maintenance_mode'"
            );

            if ($setting && $setting['setting_value'] === '1') {
                $session = new Session();
                if ($session->getUserRole() === 'admin') {
                    return null;
                }

                $messageSetting = $db->queryOne(
                    "SELECT setting_value FROM site_settings WHERE setting_key = 'maintenance_message'"
                );
                $message = $messageSetting['setting_value'] ?? 'We are currently performing maintenance.';

                return Response::html(
                    '<html><head><title>Maintenance</title></head><body style="display:flex;align-items:center;justify-content:center;height:100vh;font-family:sans-serif;background:#f8f9fa;">' .
                    '<div style="text-align:center;"><h1>Under Maintenance</h1><p>' . htmlspecialchars($message) . '</p></div></body></html>',
                    503
                );
            }
        } catch (\Exception $e) {
            // If DB is unavailable, skip maintenance check
        }

        return null;
    }
}
