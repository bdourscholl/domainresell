<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Response;
use App\Core\Validator;
use App\Core\View;
use App\Models\Announcement;

class AnnouncementController
{
    public function index(Request $request): Response
    {
        $announcements = Announcement::all();
        return View::renderWithLayout('admin/announcements', ['announcements' => $announcements], 'admin');
    }

    public function store(Request $request): Response
    {
        $validator = new Validator($request->all());
        $validator->validate(['title' => 'required', 'content' => 'required']);

        if ($validator->getErrors()) {
            flash('error', $validator->getFirstError());
            return Response::redirect('/admin/announcements');
        }

        Announcement::create([
            'title' => $request->post('title'),
            'content' => $request->post('content'),
            'type' => $request->post('type', 'info'),
            'is_active' => $request->post('is_active', '1') === '1' ? 1 : 0,
            'show_on_homepage' => $request->post('show_on_homepage', '1') === '1' ? 1 : 0,
            'created_by' => current_user_id(),
        ]);

        flash('success', 'Announcement created.');
        return Response::redirect('/admin/announcements');
    }

    public function delete(Request $request): Response
    {
        Announcement::delete((int) $request->param('id'));
        flash('success', 'Announcement deleted.');
        return Response::redirect('/admin/announcements');
    }
}
