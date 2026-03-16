<?php

require_once ROOT . "app/Models/Model.php";

class Order extends Model
{
    protected $table = "orders";
    protected $columns = [
        "id",
        "user_id",
        "room_no",
        "notes",
        "total_amount",
        "status",
        "created_at"
    ];

    public static function getByUser(int $userId)
    {
        $instance = new static();
        $connection = $instance->getConnection();

        $result = $connection->query(
            "SELECT * FROM orders
             WHERE user_id = $userId
             ORDER BY created_at DESC"
        );

        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    public static function getByDateRange(int $userId, string $dateFrom, string $dateTo)
    {
        $instance = new static();
        $connection = $instance->getConnection();

        $dateFrom = $connection->real_escape_string($dateFrom);
        $dateTo = $connection->real_escape_string($dateTo);

        $result = $connection->query(
            "SELECT * FROM orders
             WHERE user_id = $userId
               AND DATE(created_at) BETWEEN '$dateFrom' AND '$dateTo'
             ORDER BY created_at DESC"
        );

        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    public static function updateStatus(int $id, string $newStatus)
    {
        $instance = new static();
        $connection = $instance->getConnection();

        $newStatus = $connection->real_escape_string($newStatus);
        $connection->query(
            "UPDATE orders SET status = '$newStatus' WHERE id = $id"
        );
    }

    public static function getByStatus(string $status)
    {
        $instance = new static();
        $connection = $instance->getConnection();

        $status = $connection->real_escape_string($status);

        $result = $connection->query(
            "SELECT orders.*, users.name AS user_name, users.ext AS user_ext
             FROM orders
             JOIN users ON orders.user_id = users.id
             WHERE orders.status = '$status'
             ORDER BY orders.created_at DESC"
        );

        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    public static function getLatest(int $userId)
    {
        $instance = new static();
        $connection = $instance->getConnection();

        $result = $connection->query(
            "SELECT * FROM orders
             WHERE user_id = $userId
             ORDER BY created_at DESC
             LIMIT 1"
        );

        return $result->fetch_assoc();
    }

    public static function getPaginated(int $userId, int $page = 1, int $perPage = 10)
    {
        $instance = new static();
        $connection = $instance->getConnection();

        $offset = ($page - 1) * $perPage;

        $result = $connection->query(
            "SELECT * FROM orders
             WHERE user_id = $userId
             ORDER BY created_at DESC
             LIMIT $perPage OFFSET $offset"
        );

        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    public static function countByUser(int $userId): int
    {
        $instance = new static();
        $connection = $instance->getConnection();

        $result = $connection->query(
            "SELECT COUNT(*) AS total FROM orders WHERE user_id = $userId"
        );

        $row = $result->fetch_assoc();
        return (int) $row['total'];
    }
}
