<?php

namespace App\Services\Contracts;

interface UserServiceInterface
{
    public function getAllUsers(): array;
    public function getUserById(int $userId): ?array;
    public function createUser(array $data, ?array $file = null): int;
    public function updateUser(int $userId, array $data, ?array $file = null): bool;
    public function deleteOrDeactivateUser(int $userId): bool;
}
