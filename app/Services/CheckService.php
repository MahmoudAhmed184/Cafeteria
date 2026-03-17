<?php

namespace App\Services;

use App\Services\Contracts\CheckServiceInterface;
use PDO;

class CheckService implements CheckServiceInterface
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getUserSpendingSummary(?string $dateFrom, ?string $dateTo, ?int $userId = null): array
    {
        $sql = "SELECT u.id, u.name, SUM(o.total_amount) as total_spent 
                FROM users u 
                JOIN orders o ON u.id = o.user_id 
                WHERE o.status != 'Cancelled'";
        $params = [];

        if ($dateFrom) {
            $sql .= " AND DATE(o.created_at) >= :date_from";
            $params['date_from'] = $dateFrom;
        }

        if ($dateTo) {
            $sql .= " AND DATE(o.created_at) <= :date_to";
            $params['date_to'] = $dateTo;
        }

        if ($userId) {
            $sql .= " AND u.id = :user_id";
            $params['user_id'] = $userId;
        }

        $sql .= " GROUP BY u.id, u.name ORDER BY total_spent DESC";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserOrdersInRange(int $userId, ?string $dateFrom, ?string $dateTo): array
    {
        $sql = "SELECT id, created_at, total_amount 
                FROM orders 
                WHERE user_id = :user_id AND status != 'Cancelled'";
        $params = ['user_id' => $userId];

        if ($dateFrom) {
            $sql .= " AND DATE(created_at) >= :date_from";
            $params['date_from'] = $dateFrom;
        }

        if ($dateTo) {
            $sql .= " AND DATE(created_at) <= :date_to";
            $params['date_to'] = $dateTo;
        }

        $sql .= " ORDER BY created_at DESC";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOrderDetails(int $orderId): array
    {
        $sql = "SELECT oi.quantity, oi.price_at_time_of_order, p.name as product_name, p.image 
                FROM order_items oi 
                JOIN products p ON oi.product_id = p.id 
                WHERE oi.order_id = :order_id";
        
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['order_id' => $orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
