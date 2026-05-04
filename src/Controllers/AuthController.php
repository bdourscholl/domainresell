<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Core\Validator;
use App\Core\View;
use App\Models\User;
use App\Models\Cart;
use App\Models\Wallet;
use App\Services\EmailService;

class AuthController
{
    public function loginForm(Request $request): Response
    {
        return View::renderWithLayout('auth/login', [], 'main');
    }

    public function login(Request $request): Response
    {
        $validator = new Validator($request->all());
        $validator->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->getErrors()) {
            flash('error', $validator->getFirstError());
            return Response::redirect('/login');
        }

        $user = User::findByEmail($request->post('email'));

        if (!$user || !password_verify($request->post('password'), $user['password'])) {
            flash('error', __('auth.invalid_credentials'));
            return Response::redirect('/login');
        }

        if ($user['status'] !== 'active') {
            flash('error', __('auth.account_suspended'));
            return Response::redirect('/login');
        }

        $session = new Session();
        $session->set('user_id', $user['id']);
        $session->set('user_role', $user['role']);
        $session->set('user_name', $user['name']);

        User::update((int) $user['id'], [
            'last_login_at' => date('Y-m-d H:i:s'),
            'last_login_ip' => $request->ip(),
        ]);

        Cart::mergeGuestCart(session_id(), (int) $user['id']);

        $redirect = $session->get('redirect_after_login', '/account');
        $session->remove('redirect_after_login');

        if ($user['role'] === 'admin') {
            $redirect = '/admin';
        }

        return Response::redirect($redirect);
    }

    public function registerForm(Request $request): Response
    {
        $ref = $request->get('ref', '');
        return View::renderWithLayout('auth/register', ['ref' => $ref], 'main');
    }

    public function register(Request $request): Response
    {
        if (setting('registration_enabled', '1') !== '1') {
            flash('error', 'Registration is currently disabled.');
            return Response::redirect('/register');
        }

        $validator = new Validator($request->all());
        $validator->validate([
            'name' => 'required|min:2|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        if ($validator->getErrors()) {
            flash('error', $validator->getFirstError());
            return Response::redirect('/register');
        }

        $referredBy = null;
        $refCode = $request->post('ref_code');
        if ($refCode) {
            $referrer = User::findByReferralCode($refCode);
            if ($referrer) {
                $referredBy = (int) $referrer['id'];
            }
        }

        $userId = User::create([
            'name' => $request->post('name'),
            'email' => $request->post('email'),
            'password' => password_hash($request->post('password'), PASSWORD_BCRYPT),
            'role' => 'customer',
            'status' => 'active',
            'referral_code' => User::generateReferralCode(),
            'referred_by' => $referredBy,
            'email_verified_at' => date('Y-m-d H:i:s'),
        ]);

        Wallet::findByUser($userId);

        $emailService = new EmailService();
        $emailService->sendTemplate('welcome', $request->post('email'), [
            'name' => $request->post('name'),
            'email' => $request->post('email'),
            'app_name' => setting('site_name', 'Domain Reseller'),
        ]);

        $session = new Session();
        $session->set('user_id', $userId);
        $session->set('user_role', 'customer');
        $session->set('user_name', $request->post('name'));

        flash('success', __('auth.registration_success'));
        return Response::redirect('/account');
    }

    public function forgotPasswordForm(Request $request): Response
    {
        return View::renderWithLayout('auth/forgot-password', [], 'main');
    }

    public function forgotPassword(Request $request): Response
    {
        $email = $request->post('email');
        $user = User::findByEmail($email);

        if ($user) {
            $token = bin2hex(random_bytes(32));
            User::update((int) $user['id'], [
                'reset_token' => $token,
                'reset_token_expires' => date('Y-m-d H:i:s', strtotime('+1 hour')),
            ]);

            $emailService = new EmailService();
            $emailService->sendTemplate('password_reset', $email, [
                'name' => $user['name'],
                'reset_link' => url("reset-password?token={$token}"),
            ]);
        }

        flash('success', 'If the email exists, a reset link has been sent.');
        return Response::redirect('/forgot-password');
    }

    public function resetPasswordForm(Request $request): Response
    {
        $token = $request->get('token', '');
        return View::renderWithLayout('auth/forgot-password', ['token' => $token, 'reset' => true], 'main');
    }

    public function resetPassword(Request $request): Response
    {
        $token = $request->post('token');
        $user = User::findByResetToken($token);

        if (!$user) {
            flash('error', 'Invalid or expired reset token.');
            return Response::redirect('/login');
        }

        $validator = new Validator($request->all());
        $validator->validate(['password' => 'required|min:8|confirmed']);

        if ($validator->getErrors()) {
            flash('error', $validator->getFirstError());
            return Response::redirect("/reset-password?token={$token}");
        }

        User::update((int) $user['id'], [
            'password' => password_hash($request->post('password'), PASSWORD_BCRYPT),
            'reset_token' => null,
            'reset_token_expires' => null,
        ]);

        flash('success', 'Password has been reset. Please login.');
        return Response::redirect('/login');
    }

    public function logout(Request $request): Response
    {
        $session = new Session();
        session_destroy();
        return Response::redirect('/login');
    }
}
