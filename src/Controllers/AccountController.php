<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Core\Validator;
use App\Core\View;
use App\Models\User;
use App\Models\Domain;
use App\Models\Order;
use App\Services\FileUploadService;

class AccountController
{
    public function dashboard(Request $request): Response
    {
        $userId = current_user_id();
        $domains = Domain::findByUser($userId, 5);
        $orders = Order::findByUser($userId, 5);
        $domainCount = Domain::countByUser($userId);

        return View::renderWithLayout('account/dashboard', [
            'domains' => $domains,
            'orders' => $orders,
            'domain_count' => $domainCount,
        ], 'main');
    }

    public function profile(Request $request): Response
    {
        $user = User::find(current_user_id());
        return View::renderWithLayout('account/profile', ['user' => $user], 'main');
    }

    public function updateProfile(Request $request): Response
    {
        $validator = new Validator($request->all());
        $validator->validate([
            'name' => 'required|min:2|max:255',
            'phone' => 'max:50',
        ]);

        if ($validator->getErrors()) {
            flash('error', $validator->getFirstError());
            return Response::redirect('/account/profile');
        }

        $data = [
            'name' => $request->post('name'),
            'phone' => $request->post('phone'),
            'company' => $request->post('company'),
            'address' => $request->post('address'),
            'city' => $request->post('city'),
            'state' => $request->post('state'),
            'country' => $request->post('country'),
            'zip_code' => $request->post('zip_code'),
        ];

        if ($request->hasFile('avatar')) {
            $uploadService = new FileUploadService();
            $avatarPath = $uploadService->upload($request->file('avatar'), 'avatars');
            if ($avatarPath) {
                $data['avatar'] = $avatarPath;
            }
        }

        User::update(current_user_id(), $data);

        $session = new Session();
        $session->set('user_name', $request->post('name'));

        flash('success', 'Profile updated successfully.');
        return Response::redirect('/account/profile');
    }

    public function changePassword(Request $request): Response
    {
        $validator = new Validator($request->all());
        $validator->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        if ($validator->getErrors()) {
            flash('error', $validator->getFirstError());
            return Response::redirect('/account/profile');
        }

        $user = User::find(current_user_id());

        if (!password_verify($request->post('current_password'), $user['password'])) {
            flash('error', 'Current password is incorrect.');
            return Response::redirect('/account/profile');
        }

        User::update(current_user_id(), [
            'password' => password_hash($request->post('password'), PASSWORD_BCRYPT),
        ]);

        flash('success', 'Password changed successfully.');
        return Response::redirect('/account/profile');
    }

    public function setTheme(Request $request): Response
    {
        $theme = $request->post('theme');
        if (!$theme) {
            $body = json_decode($request->getBody(), true);
            $theme = is_array($body) ? ($body['theme'] ?? null) : null;
        }
        if (!in_array($theme, ['light', 'dark', 'system'], true)) {
            return Response::json(['success' => false, 'error' => 'invalid theme'], 400);
        }
        $userId = current_user_id();
        if ($userId) {
            try {
                User::update($userId, ['theme_preference' => $theme]);
            } catch (\Throwable $e) {
                return Response::json(['success' => true, 'persisted' => false]);
            }
            return Response::json(['success' => true, 'persisted' => true, 'theme' => $theme]);
        }
        return Response::json(['success' => true, 'persisted' => false, 'theme' => $theme]);
    }
}
