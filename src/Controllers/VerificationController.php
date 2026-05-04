<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Models\IdentityVerification;
use App\Services\VerificationService;

class VerificationController
{
    public function index(Request $request): Response
    {
        $verification = IdentityVerification::findByUser(current_user_id());
        return View::renderWithLayout('account/verification', ['verification' => $verification], 'main');
    }

    public function submit(Request $request): Response
    {
        if (!$request->hasFile('front_image')) {
            flash('error', 'Please upload the front image of your document.');
            return Response::redirect('/account/verification');
        }

        $service = new VerificationService();
        $user = \App\Models\User::find(current_user_id());

        $result = $service->submit(current_user_id(), [
            'document_type' => $request->post('document_type', 'nid'),
            'document_number' => $request->post('document_number'),
            'user_name' => $user['name'] ?? '',
        ], [
            'front_image' => $request->file('front_image'),
            'back_image' => $request->hasFile('back_image') ? $request->file('back_image') : null,
            'selfie_image' => $request->hasFile('selfie_image') ? $request->file('selfie_image') : null,
        ]);

        if ($result['success']) {
            flash('success', 'Verification submitted. We will review it shortly.');
        } else {
            flash('error', $result['error'] ?? 'Failed to submit verification.');
        }

        return Response::redirect('/account/verification');
    }
}
