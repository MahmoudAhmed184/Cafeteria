<?php

namespace App\Controllers\Admin;

use App\Services\Contracts\UserServiceInterface;
use App\Models\Room;

use App\Controllers\BaseController;

class UserController extends BaseController
{
    private UserServiceInterface $userService;
    private Room $roomModel;

    public function __construct(UserServiceInterface $userService, Room $roomModel)
    {
        $this->userService = $userService;
        $this->roomModel = $roomModel;
    }

    public function index(): void
    {
        $this->ensureAdmin();
        $users = $this->userService->getAllUsers();
        require_once __DIR__ . '/../../Views/admin/users/index.php';
    }

    public function create(): void
    {
        $this->ensureAdmin();
        $rooms = $this->roomModel->fetchAll();
        require_once __DIR__ . '/../../Views/admin/users/form.php';
    }

    public function store(): void
    {
        if (($_POST['password'] ?? '') !== ($_POST['confirm_password'] ?? '')) {
            $_SESSION['error'] = 'Passwords do not match.';
            header('Location: /admin/users/create');
            exit;
        }

        $data = [
            'name' => filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'email' => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL),
            'password' => $_POST['password'] ?? '',
            'room_no' => filter_input(INPUT_POST, 'room_no', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'ext' => filter_input(INPUT_POST, 'ext', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
        ];

        try {
            $this->userService->createUser($data, $_FILES['profile_pic'] ?? null);
            $_SESSION['success'] = 'User created successfully.';
            header('Location: /admin/users');
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: /admin/users/create');
        }
        exit;
    }

    public function edit(int $id): void
    {
        $user = $this->userService->getUserById($id);
        if (!$user) {
            header('Location: /admin/users');
            exit;
        }
        $rooms = $this->roomModel->fetchAll();
        require_once __DIR__ . '/../../Views/admin/users/form.php';
    }

    public function update(int $id): void
    {
        if (!empty($_POST['password']) && $_POST['password'] !== ($_POST['confirm_password'] ?? '')) {
            $_SESSION['error'] = 'Passwords do not match.';
            header('Location: /admin/users/edit?id=' . $id);
            exit;
        }

        $data = [
            'name' => filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'email' => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL),
            'room_no' => filter_input(INPUT_POST, 'room_no', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'ext' => filter_input(INPUT_POST, 'ext', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'password' => $_POST['password'] ?? ''
        ];

        try {
            $this->userService->updateUser($id, $data, $_FILES['profile_pic'] ?? null);
            $_SESSION['success'] = 'User updated successfully.';
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }
        header('Location: /admin/users');
        exit;
    }

    public function delete(int $id): void
    {
        $this->ensureAdmin();
        try {
            $this->userService->deleteOrDeactivateUser($id);
            $_SESSION['success'] = 'User deleted/deactivated successfully.';
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }
        header('Location: /admin/users');
        exit;
    }
}
