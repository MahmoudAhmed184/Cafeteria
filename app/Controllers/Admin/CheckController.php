<?php

namespace App\Controllers\Admin;

use App\Models\User;
use App\Services\Contracts\CheckServiceInterface;

use App\Controllers\BaseController;

class CheckController extends BaseController
{
    private CheckServiceInterface $checkService;
    private \App\Services\Contracts\OrderServiceInterface $orderService;
    private User $userModel;

    public function __construct(CheckServiceInterface $checkService, User $userModel, \App\Services\Contracts\OrderServiceInterface $orderService)
    {
        $this->checkService = $checkService;
        $this->userModel = $userModel;
        $this->orderService = $orderService;
    }

    public function index(): void
    {
        $this->ensureAdmin();
        $dateFrom = filter_input(INPUT_GET, 'date_from', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $dateTo = filter_input(INPUT_GET, 'date_to', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $userId = filter_input(INPUT_GET, 'user_id', FILTER_VALIDATE_INT) ?: null;

        $usersList = $this->userModel->fetchAll();
        $summary = $this->checkService->getUserSpendingSummary($dateFrom, $dateTo, $userId);

        foreach ($usersList as &$user) {
            $user['order_count'] = $this->orderService->countUserOrders((int) $user['id'], $dateFrom, $dateTo);
        }
        
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
