<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Logger;

class FileUploadService
{
    private array $allowedImage = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    private int $maxSize = 5242880; // 5MB

    public function upload(array $file, string $directory): ?string
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            Logger::error('File upload error: ' . $file['error']);
            return null;
        }

        if ($file['size'] > $this->maxSize) {
            return null;
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $this->allowedImage, true)) {
            return null;
        }

        $filename = bin2hex(random_bytes(16)) . '.' . $ext;
        $uploadDir = BASE_PATH . '/storage/uploads/' . $directory;

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $path = $uploadDir . '/' . $filename;

        if (move_uploaded_file($file['tmp_name'], $path)) {
            return $directory . '/' . $filename;
        }

        return null;
    }

    public function delete(string $relativePath): void
    {
        $fullPath = BASE_PATH . '/storage/uploads/' . $relativePath;
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }
}
