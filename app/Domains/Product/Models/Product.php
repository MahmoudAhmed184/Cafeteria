<?php

require_once ROOT . "app/Models/Model.php";

class Product extends Model
{
    protected $table = "products";
    protected $columns = [
        "id",
        "name",
        "price",
        "image",
        "category_id",
        "is_available"
    ];

    public static function getAvailable()
    {
        $instance = new static();
        $connection = $instance->getConnection();

        $result = $connection->query(
            "SELECT products.*, categories.name AS category_name
             FROM products
             JOIN categories ON products.category_id = categories.id
             WHERE products.is_available = 1
             ORDER BY products.name"
        );

        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    public static function getByCategory(int $categoryId)
    {
        $instance = new static();
        $connection = $instance->getConnection();

        $result = $connection->query(
            "SELECT * FROM products
             WHERE category_id = $categoryId
             ORDER BY name"
        );

        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    public static function toggleAvailability(int $id)
    {
        $instance = new static();
        $connection = $instance->getConnection();

        $connection->query(
            "UPDATE products SET is_available = NOT is_available WHERE id = $id"
        );
    }

    public static function allWithCategory()
    {
        $instance = new static();
        $connection = $instance->getConnection();

        $result = $connection->query(
            "SELECT products.*, categories.name AS category_name
             FROM products
             LEFT JOIN categories ON products.category_id = categories.id
             ORDER BY products.name"
        );

        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }
}
