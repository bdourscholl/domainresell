<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Response;
use App\Core\Validator;
use App\Core\View;
use App\Models\User;

class ModeratorController
{
    public function index(Request $request): Response
    {
        $db = \App\Core\App::getInstance()->getDb();
        $moderators = $db->query("SELECT * FROM users WHERE role IN ('admin','moderator') ORDER BY created_at DESC");
        return View::renderWithLayout('admin/moderators', ['moderators' => $moderators], 'admin');
    }

    public function store(Request $request): Response
    {
        $validator = new Validator($request->all());
        $validator->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ]);

        if ($validator->getErrors()) {
            flash('error', $validator->getFirstError());
            return Response::redirect('/admin/moderators');
        }

        User::create([
            'name' => $request->post('name'),
            'email' => $request->post('email'),
            'password' => password_hash($request->post('password'), PASSWORD_BCRYPT),
            'role' => $request->post('role', 'moderator'),
            'status' => 'active',
            'email_verified_at' => date('Y-m-d H:i:s'),
        ]);

        flash('success', 'Moderator created.');
        return Response::redirect('/admin/moderators');
    }
}
