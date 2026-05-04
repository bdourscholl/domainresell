<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Models\SiteSetting;
use App\Models\NotificationLog;

class NotificationController
{
    public function index(Request $request): Response
    {
        $logs = NotificationLog::recent(50);
        return View::renderWithLayout('admin/notifications', ['logs' => $logs], 'admin');
    }

    public function update(Request $request): Response
    {
        SiteSetting::set('telegram_enabled', $request->post('telegram_enabled', '0'), 'notification');
        SiteSetting::set('whatsapp_enabled', $request->post('whatsapp_enabled', '0'), 'notification');
        SiteSetting::set('email_enabled', $request->post('email_enabled', '0'), 'notification');

        flash('success', 'Notification settings updated.');
        return Response::redirect('/admin/notifications');
    }
}
