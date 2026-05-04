<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Models\User;
use App\Models\Wallet;
use App\Services\SocialAuth\SocialAuthFactory;

class SocialAuthController
{
    public function redirect(Request $request): Response
    {
        $provider = $request->param('provider');
        $auth = SocialAuthFactory::create($provider);

        if (!$auth->isEnabled()) {
            flash('error', 'This login method is not available.');
            return Response::redirect('/login');
        }

        return Response::redirect($auth->getRedirectUrl());
    }

    public function callback(Request $request): Response
    {
        $provider = $request->param('provider');
        $code = $request->get('code', '');

        if (!$code) {
            flash('error', 'Authentication failed.');
            return Response::redirect('/login');
        }

        try {
            $auth = SocialAuthFactory::create($provider);
            $userData = $auth->handleCallback($code);

            if (!$userData || empty($userData['email'])) {
                flash('error', 'Could not retrieve account information.');
                return Response::redirect('/login');
            }

            $user = User::findBySocialId($userData['provider'], $userData['social_id']);

            if (!$user) {
                $user = User::findByEmail($userData['email']);
                if ($user) {
                    User::update((int) $user['id'], [
                        'social_provider' => $userData['provider'],
                        'social_id' => $userData['social_id'],
                        'avatar' => $userData['avatar'] ?? $user['avatar'],
                    ]);
                } else {
                    $userId = User::create([
                        'name' => $userData['name'],
                        'email' => $userData['email'],
                        'social_provider' => $userData['provider'],
                        'social_id' => $userData['social_id'],
                        'avatar' => $userData['avatar'] ?? null,
                        'role' => 'customer',
                        'status' => 'active',
                        'referral_code' => User::generateReferralCode(),
                        'email_verified_at' => date('Y-m-d H:i:s'),
                    ]);
                    Wallet::findByUser($userId);
                    $user = User::find($userId);
                }
            }

            if ($user['status'] !== 'active') {
                flash('error', __('auth.account_suspended'));
                return Response::redirect('/login');
            }

            $session = new Session();
            $session->set('user_id', $user['id']);
            $session->set('user_role', $user['role']);
            $session->set('user_name', $user['name']);

            return Response::redirect('/account');
        } catch (\Exception $e) {
            flash('error', 'Authentication error. Please try again.');
            return Response::redirect('/login');
        }
    }
}
