<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Models\HomepageStat;
use App\Models\HomepageSection;
use App\Models\ActivityLog;

class HomepageController
{
    public function index(Request $request): Response
    {
        return View::renderWithLayout('admin/homepage', [
            'stats' => HomepageStat::all(),
            'sections' => HomepageSection::all(),
        ], 'admin');
    }

    public function updateStats(Request $request): Response
    {
        $stats = $request->post('stats', []);
        if (is_array($stats)) {
            foreach ($stats as $id => $data) {
                HomepageStat::update((int) $id, [
                    'stat_label' => $data['label'] ?? '',
                    'stat_value' => $data['value'] ?? '',
                    'icon' => $data['icon'] ?? '',
                    'is_active' => isset($data['active']) ? 1 : 0,
                    'sort_order' => (int) ($data['sort_order'] ?? 0),
                ]);
            }
        }

        ActivityLog::log('homepage.stats_updated', 'Updated homepage statistics');
        flash('success', 'Homepage stats updated.');
        return Response::redirect('/admin/homepage');
    }

    public function updateSection(Request $request): Response
    {
        $sectionId = (int) $request->param('id');
        HomepageSection::update($sectionId, [
            'section_title' => $request->post('section_title'),
            'section_subtitle' => $request->post('section_subtitle'),
            'content' => $request->post('content'),
            'is_active' => $request->post('is_active', '0') === '1' ? 1 : 0,
            'sort_order' => (int) $request->post('sort_order', '0'),
        ]);

        ActivityLog::log('homepage.section_updated', "Updated section #{$sectionId}");
        flash('success', 'Section updated.');
        return Response::redirect('/admin/homepage');
    }
}
