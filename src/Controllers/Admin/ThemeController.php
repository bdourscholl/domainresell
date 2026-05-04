<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Models\Theme;
use App\Services\ThemeService;

class ThemeController
{
    public function index(Request $request): Response
    {
        $themes = Theme::all();
        return View::renderWithLayout('admin/themes', ['themes' => $themes], 'admin');
    }

    public function upload(Request $request): Response
    {
        if (!$request->hasFile('theme_zip')) {
            flash('error', 'Please select a theme ZIP file.');
            return Response::redirect('/admin/themes');
        }

        $themeService = new ThemeService();
        $result = $themeService->install($request->file('theme_zip'));

        flash($result['success'] ? 'success' : 'error', $result['success'] ? 'Theme installed.' : ($result['error'] ?? 'Installation failed.'));
        return Response::redirect('/admin/themes');
    }

    public function activateHome(Request $request): Response
    {
        $slug = $request->post('slug');
        $themeService = new ThemeService();
        $themeService->activateForHome($slug);
        flash('success', 'Homepage theme activated.');
        return Response::redirect('/admin/themes');
    }

    public function activateAccount(Request $request): Response
    {
        $slug = $request->post('slug');
        $themeService = new ThemeService();
        $themeService->activateForAccount($slug);
        flash('success', 'Account theme activated.');
        return Response::redirect('/admin/themes');
    }

    public function delete(Request $request): Response
    {
        $slug = $request->param('slug');
        if ($slug === 'default') {
            flash('error', 'Cannot delete default theme.');
            return Response::redirect('/admin/themes');
        }

        $themeService = new ThemeService();
        $themeService->delete($slug);
        flash('success', 'Theme deleted.');
        return Response::redirect('/admin/themes');
    }
}
