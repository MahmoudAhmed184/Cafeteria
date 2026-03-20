<?php

namespace App\Services;

use Exception;

class FileUploadService
{
    private string $uploadRoot;
    private int $maxSize;

    public function __construct(string $uploadRoot = null)
    {
        $this->uploadRoot = $uploadRoot ?? __DIR__ . '/../../uploads/';
        $this->maxSize = defined('UPLOAD_MAX_SIZE') ? (int)UPLOAD_MAX_SIZE : 2 * 1024 * 1024;
    }

    public function uploadImage(array $file, string $subDir, string $prefix): string
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("File upload failed with error code: " . $file['error']);
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, $allowedTypes, true)) {
            throw new Exception("Invalid file type. Only JPEG, PNG, GIF, and WebP are allowed.");
        }

        if ($file['size'] > $this->maxSize) {
            throw new Exception("File size too large. Maximum allowed is " . ($this->maxSize / 1024 / 1024) . "MB.");
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid($prefix . '_', true) . '.' . $extension;
        $destinationDir = rtrim($this->uploadRoot, '/') . '/' . trim($subDir, '/');

        if (!is_dir($destinationDir)) {
            mkdir($destinationDir, 0755, true);
        }

        $destination = $destinationDir . '/' . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            throw new Exception("Failed to move uploaded file.");
        }

        return $filename;
    }
}