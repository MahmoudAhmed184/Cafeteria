<?php

namespace App\Models;

use PDO;

class OrderItem
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function create(int $orderId, int $productId, int $quantity, float $price): bool
    {
        $statement = $this->connection->prepare(
            'INSERT INTO order_items (order_id, product_id, quantity, price_at_time_of_order)
             VALUES (:order_id, :product_id, :quantity, :price)'
        );
        return $statement->execute([
            'order_id' => $orderId,
            'product_id' => $productId,
            'quantity' => $quantity,
            'price' => $price
        ]);
    }

    public function getByOrderId(int $orderId): array
    {
        $sql = 'SELECT oi.*, p.name as product_name, p.image 
                FROM order_items oi 
                JOIN products p ON oi.product_id = p.id 
                WHERE oi.order_id = :order_id';
        
        $statement = $this->connection->prepare($sql);
        $statement->execute(['order_id' => $orderId]);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
