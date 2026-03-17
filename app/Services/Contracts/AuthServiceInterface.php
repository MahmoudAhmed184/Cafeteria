<?php

namespace App\Services\Contracts;

interface AuthServiceInterface
{
    public function login(string $email, string $password): array|false;
    public function logout(): void;
    public function initiatePasswordReset(string $email): bool;
}
