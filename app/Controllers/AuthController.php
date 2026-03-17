<?php

namespace App\Controllers;

use App\Services\Contracts\AuthServiceInterface;

class AuthController
{
    private AuthServiceInterface $authService;

    public function __construct(AuthServiceInterface $authService)
    {
        $this->authService = $authService;
    }

    public function showLoginForm(): void
    {
        require_once __DIR__ . '/../Views/auth/login.php';
    }

    public function login(): void
    {
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';

        if (!$email || !$password) {
            $_SESSION['error'] = 'Email and Password are required.';
            header('Location: /login');
            exit;
        }

        $user = $this->authService->login($email, $password);

        if ($user) {
            if ((int)$user['role_id'] === 1) {
                header('Location: /admin/orders');
            } else {
                header('Location: /dashboard');
            }
            exit;
        }

        $_SESSION['error'] = 'Invalid credentials.';
        header('Location: /login');
        exit;
    }

    public function logout(): void
    {
        $this->authService->logout();
        header('Location: /login');
        exit;
    }

    public function showForgetPasswordForm(): void
    {
        require_once __DIR__ . '/../Views/auth/forget_password.php';
    }

    public function resetPassword(): void
    {
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        
        if ($email && $this->authService->initiatePasswordReset($email)) {
            $_SESSION['success'] = 'Password reset instructions sent to your email.';
        } else {
            $_SESSION['error'] = 'Email not found.';
        }
        
        header('Location: /forget-password');
        exit;
    }
}
