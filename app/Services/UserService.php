<?php

namespace App\Services;

use App\Models\User;
use App\Services\Contracts\UserServiceInterface;
use Exception;
use PDO;

class UserService implements UserServiceInterface
{
    private User $userModel;
    private FileUploadService $fileUploadService;

    public function __construct(User $userModel, FileUploadService $fileUploadService)
    {
        $this->userModel = $userModel;
        $this->fileUploadService = $fileUploadService;
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
        }
        else {
            unset($data['password']);
        }

        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $data['profile_pic'] = $this->uploadImage($file);
        }

        return $this->userModel->update($userId, $data);
    }

    public function deleteOrDeactivateUser(int $userId): bool
    {
        $orderCount = $this->userModel->getOrderCount($userId);

        if ($orderCount > 0) {
            return $this->userModel->deactivate($userId);
        }

        return $this->userModel->delete($userId);
    }

    private function uploadImage(array $file): string
    {
        return $this->fileUploadService->uploadImage($file, 'profiles', 'usr');
    }
}