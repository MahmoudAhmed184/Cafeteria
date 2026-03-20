<?php

namespace App\Services;

use PDO;
use Exception;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\Contracts\OrderServiceInterface;

class OrderService implements OrderServiceInterface
{
    private PDO $connection;
    private Order $orderModel;
    private OrderItem $orderItemModel;

    public function __construct(PDO $connection, Order $orderModel, OrderItem $orderItemModel)
    {
        $this->connection = $connection;
        $this->orderModel = $orderModel;
        $this->orderItemModel = $orderItemModel;
    }

    public function createOrder(int $userId, string $roomNo, ?string $notes, array $cartItems, float $totalAmount): int
    {
        try {
            $this->connection->beginTransaction();

            $orderId = $this->orderModel->create($userId, $roomNo, $notes, $totalAmount);

            foreach ($cartItems as $item) {
                $this->orderItemModel->create(
                    $orderId, 
                    $item['product_id'], 
                    $item['quantity'], 
                    $item['price']
                );
            }

            $this->connection->commit();
            return $orderId;
        } catch (Exception $e) {
            $this->connection->rollBack();
            throw $e;
        }
    }

    public function getUserOrders(int $userId, ?string $dateFrom = null, ?string $dateTo = null, int $limit = 10, int $offset = 0): array
    {
        return $this->orderModel->getUserOrders($userId, $dateFrom, $dateTo, $limit, $offset);
    }

    public function getAllProcessingOrders(): array
    {
        return $this->orderModel->getAllProcessing();
    }

    public function getOrderItems(int $orderId): array
    {
        return $this->orderItemModel->getByOrderId($orderId);
    }

    public function getOrderById(int $orderId): ?array
    {
        $order = $this->orderModel->findById($orderId);
        return $order ?: null;
    }

    public function countUserOrders(int $userId, ?string $dateFrom, ?string $dateTo): int
    {
        return $this->orderModel->countUserOrders($userId, $dateFrom, $dateTo);
    }


    public function cancelOrder(int $orderId, int $userId): bool
    {
        $order = $this->orderModel->findById($orderId);
        
        if ($order && (int)$order['user_id'] === $userId && $order['status'] === 'Processing') {
            return $this->orderModel->updateStatus($orderId, 'Cancelled');
        }
        
        return false;
    }

    public function updateOrderStatus(int $orderId, string $status): bool
    {
        return $this->orderModel->updateStatus($orderId, $status);
    }

    public function getSpendingSummary(?string $dateFrom, ?string $dateTo, ?int $userId = null): array
    {
        return $this->orderModel->getSpendingSummary($dateFrom, $dateTo, $userId);
    }

    public function getOrdersInRange(int $userId, ?string $dateFrom, ?string $dateTo): array
    {
        return $this->orderModel->getOrdersInRange($userId, $dateFrom, $dateTo);
    }
}
