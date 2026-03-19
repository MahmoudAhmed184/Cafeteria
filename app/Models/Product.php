<?php

namespace App\Models;

use PDO;

class Product
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function fetchAll(bool $availableOnly = false): array
    {
        $sql = 'SELECT p.*, c.name as category_name 
                FROM products p 
                JOIN categories c ON p.category_id = c.id';
        
        if ($availableOnly) {
            $sql .= ' WHERE p.is_available = 1';
        }
        
        $sql .= ' ORDER BY p.name ASC';
        
        return $this->connection->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): array|false
    {
        $stmt = $this->connection->prepare('SELECT * FROM products WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create(array $data): int
    {
        $stmt = $this->connection->prepare(
            'INSERT INTO products (name, price, image, category_id) VALUES (:name, :price, :image, :category_id)'
        );
        $stmt->execute([
            'name' => $data['name'],
            'price' => $data['price'],
            'image' => $data['image'],
            'category_id' => $data['category_id']
        ]);
        return (int) $this->connection->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $sql = 'UPDATE products SET name = :name, price = :price, category_id = :category_id';
        $params = [
            'name' => $data['name'],
            'price' => $data['price'],
            'category_id' => $data['category_id'],
            'id' => $id
        ];

        if (isset($data['image'])) {
            $sql .= ', image = :image';
            $params['image'] = $data['image'];
        }

        $sql .= ' WHERE id = :id';
        
        return $this->connection->prepare($sql)->execute($params);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->connection->prepare(
            'SELECT COUNT(*) FROM order_items WHERE product_id = :id'
        );
        $stmt->execute(['id' => $id]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            $stmt = $this->connection->prepare('UPDATE products SET is_available = 0 WHERE id = :id');
            return $stmt->execute(['id' => $id]);
        }

        $stmt = $this->connection->prepare('DELETE FROM products WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }

    public function toggleAvailability(int $id): bool
    {
        $stmt = $this->connection->prepare('UPDATE products SET is_available = NOT is_available WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}
