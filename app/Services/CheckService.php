<?php

namespace App\Services;

use App\Services\Contracts\CheckServiceInterface;
use App\Services\Contracts\OrderServiceInterface;

class CheckService implements CheckServiceInterface
{
    private OrderServiceInterface $orderService;

    public function __construct(OrderServiceInterface $orderService)
    {
        $this->orderService = $orderService;
    }

    public function getUserSpendingSummary(?string $dateFrom, ?string $dateTo, ?int $userId = null): array
    {
        return $this->orderService->getSpendingSummary($dateFrom, $dateTo, $userId);
    }

    public function getUserOrdersInRange(int $userId, ?string $dateFrom, ?string $dateTo): array
    {
        return $this->orderService->getOrdersInRange($userId, $dateFrom, $dateTo);
    }

    public function getOrderDetails(int $orderId): array
    {
        return $this->orderService->getOrderItems($orderId);
    }
}
