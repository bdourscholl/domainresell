<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\App;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;

class VerificationMiddleware
{
    public function handle(Request $request): ?Response
    {
        $session = new Session();

        if (!$session->isLoggedIn()) {
            return null;
        }

        try {
            $app = App::getInstance();
            $db = $app->getDb();

            $setting = $db->queryOne(
                "SELECT setting_value FROM site_settings WHERE setting_key = 'verification_required'"
            );

            if (!$setting || $setting['setting_value'] !== '1') {
                return null;
            }

            $userId = $session->getUserId();
            $verification = $db->queryOne(
                "SELECT status FROM identity_verifications WHERE user_id = ? ORDER BY id DESC LIMIT 1",
                [$userId]
            );

            if (!$verification || $verification['status'] !== 'approved') {
                $session->flash('warning', __('auth.verification_required'));
                return Response::redirect('/account/verification');
            }
        } catch (\Exception $e) {
            // Skip check on error
        }

        return null;
    }
}
