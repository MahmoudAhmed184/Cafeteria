<?php

namespace App\Services;

use App\Services\Contracts\ManualOrderServiceInterface;
use App\Services\Contracts\OrderServiceInterface;
use Exception;
use PDO;
use App\Models\User;

class ManualOrderService implements ManualOrderServiceInterface
{
    private User $userModel;
    private OrderServiceInterface $orderService;

    public function __construct(User $userModel, OrderServiceInterface $orderService)
    {
        $this->userModel = $userModel;
        $this->orderService = $orderService;
    }

    public function searchUsers(string $searchTerm = ''): array
    {
        return $this->userModel->searchActiveUsers(trim($searchTerm));
    }

    public function placeOrderForUser(int $userId, string $roomNo, ?string $notes, array $cartItems, float $totalAmount): int
    {
        if (!$this->userModel->isActiveUser($userId)) {
            throw new Exception('Selected user not found or is deactivated.');
        }

        if ($cartItems === []) {
            throw new Exception('Cart is empty.');
        }

        return $this->orderService->createOrder($userId, $roomNo, $notes, $cartItems, $totalAmount);
    }
}
