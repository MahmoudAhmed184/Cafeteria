<?php

namespace App\Controllers\Admin;

use App\Services\Contracts\OrderServiceInterface;

class AdminOrderController
{
    private OrderServiceInterface $orderService;

    public function __construct(OrderServiceInterface $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(): void
    {
        $orders = $this->orderService->getAllProcessingOrders();
        require_once __DIR__ . '/../../Views/admin/orders.php';
    }

    public function deliver(int $orderId): void
    {
        $this->orderService->updateOrderStatus($orderId, 'Out for Delivery');
        header('Location: /admin/orders');
        exit;
    }

    public function done(int $orderId): void
    {
        $this->orderService->updateOrderStatus($orderId, 'Done');
        header('Location: /admin/orders');
        exit;
    }

    public function items(int $orderId): void
    {
        $items = $this->orderService->getOrderItems($orderId);
        header('Content-Type: application/json');
        echo json_encode($items);
        exit;
    }
}
