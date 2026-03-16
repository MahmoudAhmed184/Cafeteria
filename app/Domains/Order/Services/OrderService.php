<?php

require_once ROOT . "app/Domains/Order/Models/Order.php";
require_once ROOT . "app/Domains/Order/Models/OrderItem.php";
require_once ROOT . "app/Database/Database.php";

class OrderService
{

    public function createOrder(int $userId, string $roomNo, ?string $notes, array $items): array
    {
        if (empty($items)) {
            return [
                "success" => false,
                "message" => "Cannot create an order with an empty cart."
            ];
        }

        $db = Database::connect();
        $connection = $db->getConnectionInstance();

        $connection->begin_transaction();

        try {
            $totalAmount = 0;
            foreach ($items as $item) {
                $totalAmount += $item['price'] * $item['quantity'];
            }

            $roomNo = $connection->real_escape_string($roomNo);
            $notes = $notes ? "'" . $connection->real_escape_string($notes) . "'" : "NULL";

            $connection->query(
                "INSERT INTO orders (user_id, room_no, notes, total_amount, status)
                 VALUES ($userId, '$roomNo', $notes, $totalAmount, 'Processing')"
            );

            $orderId = $connection->insert_id;

            $values = [];
            foreach ($items as $item) {
                $productId = (int) $item['product_id'];
                $quantity = (int) $item['quantity'];
                $price = (float) $item['price'];
                $values[] = "($orderId, $productId, $quantity, $price)";
            }
            $valueString = implode(", ", $values);

            $connection->query(
                "INSERT INTO order_items (order_id, product_id, quantity, price_at_time_of_order)
                 VALUES $valueString"
            );

            $connection->commit();

            return [
                "success" => true,
                "message" => "Order placed successfully!",
                "order_id" => $orderId
            ];

        } catch (\Exception $e) {
            $connection->rollback();

            return [
                "success" => false,
                "message" => "Failed to place order. Please try again."
            ];
        }
    }

    public function cancelOrder(int $orderId, int $userId): array
    {
        $order = Order::find($orderId);

        if (!$order) {
            return ["success" => false, "message" => "Order not found."];
        }

        if ($order['user_id'] != $userId) {
            return ["success" => false, "message" => "You can only cancel your own orders."];
        }

        if ($order['status'] !== 'Processing') {
            return [
                "success" => false,
                "message" => "This order can no longer be cancelled. Current status: " . $order['status']
            ];
        }

        Order::updateStatus($orderId, 'Cancelled');

        return ["success" => true, "message" => "Order cancelled successfully."];
    }

    public function updateOrderStatus(int $orderId, string $newStatus): array
    {
        $order = Order::find($orderId);

        if (!$order) {
            return ["success" => false, "message" => "Order not found."];
        }

        $validTransitions = [
            'Processing' => 'Out for Delivery',
            'Out for Delivery' => 'Done',
        ];

        $currentStatus = $order['status'];

        if (!isset($validTransitions[$currentStatus]) || $validTransitions[$currentStatus] !== $newStatus) {
            return [
                "success" => false,
                "message" => "Cannot change status from '$currentStatus' to '$newStatus'."
            ];
        }

        Order::updateStatus($orderId, $newStatus);

        return ["success" => true, "message" => "Order status updated to '$newStatus'."];
    }

    public function getOrderDetails(int $orderId): array
    {
        $order = Order::find($orderId);
        $items = OrderItem::getByOrder($orderId);

        return [
            "order" => $order,
            "items" => $items
        ];
    }

    public function getUserOrders(int $userId, int $page = 1, int $perPage = 10): array
    {
        $orders = Order::getPaginated($userId, $page, $perPage);
        $totalOrders = Order::countByUser($userId);
        $totalPages = ceil($totalOrders / $perPage);

        return [
            "orders" => $orders,
            "current_page" => $page,
            "total_pages" => $totalPages,
            "total_orders" => $totalOrders
        ];
    }
}
