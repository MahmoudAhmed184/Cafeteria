<?php

namespace App\Controllers\Admin;

use App\Services\Contracts\OrderServiceInterface;

use App\Controllers\BaseController;

class AdminOrderController extends BaseController
{
    private OrderServiceInterface $orderService;

    public function __construct(OrderServiceInterface $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(): void
    {
        $this->ensureAdmin();
        $orders = $this->orderService->getAllProcessingOrders();

        foreach ($orders as &$order) {
            $order['items'] = $this->orderService->getOrderItems((int)$order['id']);
        }
        unset($order);

        require_once __DIR__ . '/../../Views/admin/orders.php';
    }

    public function deliver(int $orderId): void
    {
        $this->ensureAdmin();
        $this->orderService->updateOrderStatus($orderId, 'Out for Delivery');
        header('Location: /admin/orders');
        exit;
    }

    public function done(int $orderId): void
    {
        $this->ensureAdmin();
        $this->orderService->updateOrderStatus($orderId, 'Done');
        header('Location: /admin/orders');
        exit;
    }

    public function items(int $orderId): void
    {
        $this->ensureAdmin(true);
        $items = $this->orderService->getOrderItems($orderId);
        $this->respondJson(['items' => $items]);
    }
}