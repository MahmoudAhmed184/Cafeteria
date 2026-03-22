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
}
