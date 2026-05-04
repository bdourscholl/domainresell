<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\IdentityVerification;
use App\Services\Notification\NotificationManager;

class VerificationService
{
    public function submit(int $userId, array $data, array $files): array
    {
        $uploadService = new FileUploadService();

        $frontImage = $uploadService->upload($files['front_image'], 'verifications');
        if (!$frontImage) {
            return ['success' => false, 'error' => 'Failed to upload front image'];
        }

        $backImage = isset($files['back_image']) ? $uploadService->upload($files['back_image'], 'verifications') : null;
        $selfieImage = isset($files['selfie_image']) ? $uploadService->upload($files['selfie_image'], 'verifications') : null;

        $id = IdentityVerification::create([
            'user_id' => $userId,
            'document_type' => $data['document_type'] ?? 'nid',
            'document_number' => $data['document_number'] ?? null,
            'front_image' => $frontImage,
            'back_image' => $backImage,
            'selfie_image' => $selfieImage,
            'status' => 'pending',
        ]);

        $notifier = new NotificationManager();
        $notifier->notifyNewVerification([
            'user_name' => $data['user_name'] ?? '',
            'document_type' => $data['document_type'] ?? 'nid',
        ]);

        return ['success' => true, 'verification_id' => $id];
    }

    public function approve(int $id, int $adminId, ?string $notes = null): void
    {
        IdentityVerification::update($id, [
            'status' => 'approved',
            'reviewed_by' => $adminId,
            'reviewed_at' => date('Y-m-d H:i:s'),
            'admin_notes' => $notes,
        ]);
    }

    public function reject(int $id, int $adminId, string $reason): void
    {
        IdentityVerification::update($id, [
            'status' => 'rejected',
            'reviewed_by' => $adminId,
            'reviewed_at' => date('Y-m-d H:i:s'),
            'admin_notes' => $reason,
        ]);
    }
}
