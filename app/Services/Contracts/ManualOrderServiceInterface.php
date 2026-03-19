<?php

namespace App\Services\Contracts;

interface ManualOrderServiceInterface
{
    public function searchUsers(string $searchTerm = ''): array;
    public function placeOrderForUser(int $userId, string $roomNo, ?string $notes, array $cartItems, float $totalAmount): int;
}
