<?php

namespace App\Controllers;

use App\Services\Contracts\OrderServiceInterface;

class OrderController
{
    private OrderServiceInterface $orderService;

    public function __construct(OrderServiceInterface $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            header('Location: /login');
            exit;
        }

        $dateFrom = filter_input(INPUT_GET, 'date_from', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $dateTo = filter_input(INPUT_GET, 'date_to', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $page = (int) filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT) ?: 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $orders = $this->orderService->getUserOrders($userId, $dateFrom, $dateTo, $limit, $offset);

        require_once __DIR__ . '/../Views/user/orders.php';
    }

    public function cancel(int $orderId): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            header('Location: /login');
            exit;
        }

        $success = $this->orderService->cancelOrder($orderId, $userId);

        if ($success) {
            $_SESSION['success'] = 'Order cancelled successfully.';
        } else {
            $_SESSION['error'] = 'Order cannot be cancelled. It may have already been processed.';
        }

        header('Location: /orders');
        exit;
    }

    public function items(int $orderId): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }

        $items = $this->orderService->getOrderItems($orderId);

        header('Content-Type: application/json');
        echo json_encode($items);
        exit;
    }
}
