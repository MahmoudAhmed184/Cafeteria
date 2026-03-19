<?php

namespace App\Services\Contracts;

interface OrderServiceInterface
{
    public function createOrder(int $userId, string $roomNo, ?string $notes, array $cartItems, float $totalAmount): int;
    public function getUserOrders(int $userId, ?string $dateFrom = null, ?string $dateTo = null, int $limit = 10, int $offset = 0): array;
    public function getAllProcessingOrders(): array;
    public function getOrderItems(int $orderId): array;
    public function cancelOrder(int $orderId, int $userId): bool;
    public function updateOrderStatus(int $orderId, string $status): bool;
}
