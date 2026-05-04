<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Models\SeoSetting;

class SeoController
{
    public function index(Request $request): Response
    {
        $seoSettings = SeoSetting::all();
        return View::renderWithLayout('admin/seo', ['seo_settings' => $seoSettings], 'admin');
    }

    public function update(Request $request): Response
    {
        $pages = $request->post('pages', []);
        if (is_array($pages)) {
            foreach ($pages as $pageKey => $data) {
                SeoSetting::upsert($pageKey, [
                    'meta_title' => $data['meta_title'] ?? '',
                    'meta_description' => $data['meta_description'] ?? '',
                    'meta_keywords' => $data['meta_keywords'] ?? '',
                ]);
            }
        }

        flash('success', 'SEO settings updated.');
        return Response::redirect('/admin/seo');
    }
}
