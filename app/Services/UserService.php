<?php

namespace App\Services;

use App\Models\User;
use App\Services\Contracts\UserServiceInterface;
use Exception;
use PDO;

class UserService implements UserServiceInterface
{
    private User $userModel;
    private PDO $connection;

    public function __construct(User $userModel, PDO $connection)
    {
        $this->userModel = $userModel;
        $this->connection = $connection;
    }

    public function getAllUsers(): array
    {
        return $this->userModel->fetchAll();
    }

    public function getUserById(int $userId): ?array
    {
        $user = $this->userModel->findById($userId);
        return $user ?: null;
    }

    public function createUser(array $data, ?array $file = null): int
    {
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $data['profile_pic'] = $this->uploadImage($file);
        }

        return $this->userModel->insert($data);
    }

    public function updateUser(int $userId, array $data, ?array $file = null): bool
    {
        if (!empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        } else {
            unset($data['password']); // Model should handle if password is absent
        }

        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $data['profile_pic'] = $this->uploadImage($file);
        }

        return $this->userModel->update($userId, $data);
    }

    public function deleteOrDeactivateUser(int $userId): bool
    {
        $stmt = $this->connection->prepare('SELECT COUNT(*) FROM orders WHERE user_id = :user_id');
        $stmt->execute(['user_id' => $userId]);
        $orderCount = $stmt->fetchColumn();

        if ($orderCount > 0) {
            return $this->userModel->deactivate($userId);
        }

        $stmt = $this->connection->prepare('DELETE FROM users WHERE id = :user_id AND is_active = 1');
        return $stmt->execute(['user_id' => $userId]);
    }

    private function uploadImage(array $file): string
    {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, $allowedTypes, true)) {
            throw new Exception("Invalid file type.");
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('usr_', true) . '.' . $extension;
        $destination = __DIR__ . '/../../uploads/profiles/' . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            throw new Exception("Failed to move uploaded file.");
        }

        return $filename;
    }
}
