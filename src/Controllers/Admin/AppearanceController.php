<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Services\FontService;

class AppearanceController
{
    public function index(Request $request): Response
    {
        $fontService = new FontService();
        return View::renderWithLayout('admin/appearance', [
            'fonts' => $fontService->getAll(),
            'active_font' => $fontService->getActiveFont(),
        ], 'admin');
    }

    public function updateFont(Request $request): Response
    {
        $fontId = (int) $request->post('font_id');
        $fontService = new FontService();
        $fontService->activate($fontId);

        flash('success', 'Font updated successfully.');
        return Response::redirect('/admin/appearance');
    }
}
