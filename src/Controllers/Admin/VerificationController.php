<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Models\IdentityVerification;
use App\Services\VerificationService;

class VerificationController
{
    public function index(Request $request): Response
    {
        $verifications = IdentityVerification::getPending();
        return View::renderWithLayout('admin/verifications', ['verifications' => $verifications], 'admin');
    }

    public function approve(Request $request): Response
    {
        $id = (int) $request->param('id');
        $service = new VerificationService();
        $service->approve($id, current_user_id(), $request->post('notes'));
        flash('success', 'Verification approved.');
        return Response::redirect('/admin/verifications');
    }

    public function reject(Request $request): Response
    {
        $id = (int) $request->param('id');
        $service = new VerificationService();
        $service->reject($id, current_user_id(), $request->post('reason', ''));
        flash('success', 'Verification rejected.');
        return Response::redirect('/admin/verifications');
    }
}
