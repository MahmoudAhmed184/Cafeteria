<?php

namespace App\Models;

use PDO;

class Cart
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getByUserId(int $userId): array
    {
        $sql = 'SELECT c.user_id, c.product_id, c.quantity, p.name, p.price, p.image
                FROM carts c
                JOIN products p ON c.product_id = p.id
                WHERE c.user_id = :user_id
                ORDER BY p.name ASC';
        $statement = $this->connection->prepare($sql);
        $statement->execute(['user_id' => $userId]);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addItem(int $userId, int $productId, int $quantity): bool
    {
        $current = $this->findItem($userId, $productId);

        if ($current) {
            return $this->setQuantity($userId, $productId, (int) $current['quantity'] + $quantity);
        }

        $statement = $this->connection->prepare(
            'INSERT INTO carts (user_id, product_id, quantity) VALUES (:user_id, :product_id, :quantity)'
        );
        return $statement->execute([
            'user_id' => $userId,
            'product_id' => $productId,
            'quantity' => $quantity,
        ]);
    }

    public function setQuantity(int $userId, int $productId, int $quantity): bool
    {
        $statement = $this->connection->prepare(
            'UPDATE carts SET quantity = :quantity WHERE user_id = :user_id AND product_id = :product_id'
        );
        return $statement->execute([
            'quantity' => $quantity,
            'user_id' => $userId,
            'product_id' => $productId,
        ]);
    }

    public function removeItem(int $userId, int $productId): bool
    {
        $statement = $this->connection->prepare(
            'DELETE FROM carts WHERE user_id = :user_id AND product_id = :product_id'
        );
        return $statement->execute([
            'user_id' => $userId,
            'product_id' => $productId,
        ]);
    }

    public function clearByUserId(int $userId): bool
    {
        $statement = $this->connection->prepare('DELETE FROM carts WHERE user_id = :user_id');
        return $statement->execute(['user_id' => $userId]);
    }

    private function findItem(int $userId, int $productId): array|false
    {
        $statement = $this->connection->prepare(
            'SELECT user_id, product_id, quantity
             FROM carts
             WHERE user_id = :user_id AND product_id = :product_id
             LIMIT 1'
        );
        $statement->execute([
            'user_id' => $userId,
            'product_id' => $productId,
        ]);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }
}
