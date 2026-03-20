<?php

namespace App\Services\Contracts;

interface CartServiceInterface
{
    public function getCartState(): array;
    public function addItem(int $productId, int $quantity = 1): void;
    public function updateItemQuantity(int $productId, int $quantity): void;
    public function removeItem(int $productId): void;
    public function clearCart(): void;
    public function validateCart(): bool;
    public function calculateTotal(): float;
    public function placeOrder(int $userId, string $roomNo, ?string $notes): int;
}
