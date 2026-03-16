<?php

require_once ROOT . "app/Domains/Order/Services/OrderService.php";
require_once ROOT . "app/Database/Database.php";

class ManualOrderService
{

    private $orderService;

    public function __construct()
    {
        $this->orderService = new OrderService();
    }

    public function searchUsers(string $searchTerm): array
    {
        $db = Database::connect();
        $connection = $db->getConnectionInstance();

        $searchTerm = $connection->real_escape_string($searchTerm);

        $result = $connection->query(
            "SELECT id, name, email, room_no, ext
             FROM users
             WHERE is_active = 1
               AND role_id = 2
               AND name LIKE '%$searchTerm%'
             ORDER BY name
             LIMIT 20"
        );

        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        return $users;
    }

    public function placeOrderForUser(int $userId, string $roomNo, ?string $notes, array $items): array
    {
        $db = Database::connect();
        $connection = $db->getConnectionInstance();

        $result = $connection->query(
            "SELECT id FROM users WHERE id = $userId AND is_active = 1"
        );

        if ($result->num_rows === 0) {
            return ["success" => false, "message" => "Selected user not found or is deactivated."];
        }

        return $this->orderService->createOrder($userId, $roomNo, $notes, $items);
    }
}
