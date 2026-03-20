<?php

namespace App\Services;

use App\Models\User;
use App\Services\Contracts\AuthServiceInterface;

class AuthService implements AuthServiceInterface
{
    private User $userModel;

    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }

    public function login(string $email, string $password): array|false
    {
        $user = $this->userModel->findByEmail($email);
        
        if ($user && password_verify($password, $user['password'])) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            session_regenerate_id(true);
            
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['role_id'] = $user['role_id'];
            $_SESSION['profile_pic'] = $user['profile_pic'];
            
            return $user;
        }
        
        return false;
    }

    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = [];
        session_destroy();
    }

    public function initiatePasswordReset(string $email): bool
    {
        $user = $this->userModel->findByEmail($email);
        if ($user) {
            $token = bin2hex(random_bytes(32));
            $logMessage = sprintf(
                "[%s] PASSWORD RESET SIMULATION for %s: Reset link http://localhost:8000/reset-password?token=%s\n",
                date('Y-m-d H:i:s'),
                $email,
                $token
            );
            $logFile = defined('LOG_FILE') ? (string) LOG_FILE : __DIR__ . '/../../logs/error.log';
            error_log($logMessage, 3, $logFile);
            return true;
        }
        return false;
    }
}
