<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Models\SiteSetting;
use App\Models\ActivityLog;

class SettingsController
{
    public function index(Request $request): Response
    {
        $settings = SiteSetting::all();
        $grouped = [];
        foreach ($settings as $s) {
            $grouped[$s['setting_group']][] = $s;
        }
        return View::renderWithLayout('admin/settings', ['settings' => $grouped], 'admin');
    }

    public function update(Request $request): Response
    {
        $settings = $request->post('settings', []);
        if (is_array($settings)) {
            foreach ($settings as $key => $value) {
                SiteSetting::set($key, (string) $value);
            }
        }

        ActivityLog::log('settings.updated', 'Updated site settings');
        flash('success', 'Settings updated.');
        return Response::redirect('/admin/settings');
    }
}
