<?php

namespace App\Models;

use PDO;

class Order
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function create(int $userId, string $roomNo, ?string $notes, float $totalAmount): int
    {
        $statement = $this->connection->prepare(
            'INSERT INTO orders (user_id, room_no, notes, total_amount, status)
             VALUES (:user_id, :room_no, :notes, :total_amount, "Processing")'
        );
        $statement->execute([
            'user_id' => $userId,
            'room_no' => $roomNo,
            'notes' => $notes,
            'total_amount' => $totalAmount
        ]);
        return (int) $this->connection->lastInsertId();
    }

    public function findById(int $id): array|false
    {
        $statement = $this->connection->prepare('SELECT * FROM orders WHERE id = :id LIMIT 1');
        $statement->execute(['id' => $id]);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserOrders(int $userId, ?string $dateFrom, ?string $dateTo, int $limit, int $offset): array
    {
        $sql = 'SELECT * FROM orders WHERE user_id = :user_id';
        $params = ['user_id' => $userId];

        if ($dateFrom) {
            $sql .= ' AND DATE(created_at) >= :date_from';
            $params['date_from'] = $dateFrom;
        }

        if ($dateTo) {
            $sql .= ' AND DATE(created_at) <= :date_to';
            $params['date_to'] = $dateTo;
        }

        $sql .= ' ORDER BY created_at DESC LIMIT ' . (int)$limit . ' OFFSET ' . (int)$offset;

        $statement = $this->connection->prepare($sql);
        $statement->execute($params);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllProcessing(): array
    {
        $sql = 'SELECT o.*, u.name as user_name, u.ext 
                FROM orders o 
                JOIN users u ON o.user_id = u.id 
                WHERE o.status = "Processing" 
                ORDER BY o.created_at ASC';
        
        $statement = $this->connection->query($sql);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatus(int $id, string $status): bool
    {
        $statement = $this->connection->prepare('UPDATE orders SET status = :status WHERE id = :id');
        return $statement->execute(['status' => $status, 'id' => $id]);
    }
}
