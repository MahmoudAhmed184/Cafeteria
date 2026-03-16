<?php

require_once ROOT . "app/Models/Model.php";

class OrderItem extends Model
{
    protected $table = "order_items";
    protected $columns = [
        "id",
        "order_id",
        "product_id",
        "quantity",
        "price_at_time_of_order"
    ];

    public static function getByOrder(int $orderId)
    {
        $instance = new static();
        $connection = $instance->getConnection();

        $result = $connection->query(
            "SELECT order_items.*, products.name AS product_name
             FROM order_items
             JOIN products ON order_items.product_id = products.id
             WHERE order_items.order_id = $orderId"
        );

        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    public static function createBatch(int $orderId, array $items)
    {
        $instance = new static();
        $connection = $instance->getConnection();

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
    }
}
