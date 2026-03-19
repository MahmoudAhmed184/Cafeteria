<?php

namespace App\Services\Contracts;

interface CheckServiceInterface
{
    public function getUserSpendingSummary(?string $dateFrom, ?string $dateTo, ?int $userId = null): array;
    public function getUserOrdersInRange(int $userId, ?string $dateFrom, ?string $dateTo): array;
    public function getOrderDetails(int $orderId): array;
}
