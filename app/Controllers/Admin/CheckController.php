<?php

namespace App\Controllers\Admin;

use App\Models\User;
use App\Services\Contracts\CheckServiceInterface;

class CheckController
{
    private CheckServiceInterface $checkService;
    private User $userModel;

    public function __construct(CheckServiceInterface $checkService, User $userModel)
    {
        $this->checkService = $checkService;
        $this->userModel = $userModel;
    }

    public function index(): void
    {
        $dateFrom = filter_input(INPUT_GET, 'date_from', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $dateTo = filter_input(INPUT_GET, 'date_to', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $userId = filter_input(INPUT_GET, 'user_id', FILTER_VALIDATE_INT) ?: null;

        $usersList = $this->userModel->fetchAll();
        $summary = $this->checkService->getUserSpendingSummary($dateFrom, $dateTo, $userId);

        require_once __DIR__ . '/../../Views/admin/checks.php';
    }

    public function userOrders(int $userId): void
    {
        $dateFrom = filter_input(INPUT_GET, 'date_from', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $dateTo = filter_input(INPUT_GET, 'date_to', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $orders = $this->checkService->getUserOrdersInRange($userId, $dateFrom, $dateTo);
        
        header('Content-Type: application/json');
        echo json_encode($orders);
        exit;
    }

    public function orderItems(int $orderId): void
    {
        $items = $this->checkService->getOrderDetails($orderId);
        
        header('Content-Type: application/json');
        echo json_encode($items);
        exit;
    }
}
