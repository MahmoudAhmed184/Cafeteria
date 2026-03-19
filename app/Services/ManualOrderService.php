<?php

namespace App\Services;

use App\Services\Contracts\ManualOrderServiceInterface;
use App\Services\Contracts\OrderServiceInterface;
use Exception;
use PDO;

class ManualOrderService implements ManualOrderServiceInterface
{
    private PDO $connection;
    private OrderServiceInterface $orderService;

    public function __construct(PDO $connection, OrderServiceInterface $orderService)
    {
        $this->connection = $connection;
        $this->orderService = $orderService;
    }

    public function searchUsers(string $searchTerm = ''): array
    {
        $normalized = trim($searchTerm);
        $query = '%' . $normalized . '%';

        $sql = 'SELECT id, name, email, room_no, ext
                FROM users
                WHERE is_active = 1
                  AND role_id = 2
                  AND (name LIKE ? OR email LIKE ?)
                ORDER BY name ASC
                LIMIT 20';
        $statement = $this->connection->prepare($sql);
        $statement->execute([$query, $query]);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function placeOrderForUser(int $userId, string $roomNo, ?string $notes, array $cartItems, float $totalAmount): int
    {
        $statement = $this->connection->prepare(
            'SELECT id FROM users WHERE id = :user_id AND is_active = 1 AND role_id = 2 LIMIT 1'
        );
        $statement->execute(['user_id' => $userId]);

        if (!$statement->fetchColumn()) {
            throw new Exception('Selected user not found or is deactivated.');
        }

        if ($cartItems === []) {
            throw new Exception('Cart is empty.');
        }

        return $this->orderService->createOrder($userId, $roomNo, $notes, $cartItems, $totalAmount);
    }
}
