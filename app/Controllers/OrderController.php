<?php

namespace App\Controllers;

use App\Services\Contracts\OrderServiceInterface;

class OrderController extends BaseController
{
    private OrderServiceInterface $orderService;

    public function __construct(OrderServiceInterface $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(): void
    {
        $this->ensureAuthenticated();

        $userId = (int)$_SESSION['user_id'];
        $dateFrom = filter_input(INPUT_GET, 'date_from', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $dateTo = filter_input(INPUT_GET, 'date_to', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $page = (int)filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT) ?: 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $orders = $this->orderService->getUserOrders($userId, $dateFrom, $dateTo, $limit, $offset);

        foreach ($orders as &$order) {
            $order['items'] = $this->orderService->getOrderItems((int)$order['id']);
        }
        unset($order);

        $totalOrders = $this->orderService->countUserOrders($userId, $dateFrom, $dateTo);
        $totalPages = (int)ceil($totalOrders / $limit);
        $currentPage = $page;

        require_once __DIR__ . '/../Views/user/orders.php';
    }

    public function cancel(int $orderId): void
    {
        $this->ensureAuthenticated();

        $userId = (int)$_SESSION['user_id'];
        $success = $this->orderService->cancelOrder($orderId, $userId);

        if ($success) {
            $_SESSION['flash_success'] = "Order #{$orderId} has been cancelled.";
        }
        else {
            $_SESSION['flash_error'] = "Unable to cancel order #{$orderId}. It may already be processing.";
        }

        $base = defined('BASE_URL') ? rtrim((string)BASE_URL, '/') : '';
        header("Location: " . ($base !== '' ? $base : '') . "/orders");
        exit;
    }

    public function items(int $orderId): void
    {
        $this->ensureAuthenticated(true);

        $order = $this->orderService->getOrderById($orderId);
        if (!$order) {
            $this->respondJson(['error' => 'Order not found'], 404);
        }

        if ((int)$order['user_id'] !== (int)$_SESSION['user_id'] && (int)($_SESSION['role_id'] ?? 0) !== 1) {
            $this->respondJson(['error' => 'Unauthorized access to order details'], 403);
        }

        $items = $this->orderService->getOrderItems($orderId);
        $this->respondJson(['items' => $items]);
    }
}