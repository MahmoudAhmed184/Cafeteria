<?php

require_once __DIR__ . '/../../config/database.php';

class Product
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::connect();
    }

    public function getAll()
    {
        $stmt = $this->pdo->query("SELECT * FROM products");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO products (name, price, category_id, image)
            VALUES (?, ?, ?, ?)
        ");

        return $stmt->execute([
            $data['name'],
            $data['price'],
            $data['category_id'],
            $data['image']
        ]);
    }

    public function update($id, $data)
    {
        $stmt = $this->pdo->prepare("
            UPDATE products
            SET name = ?, price = ?, category_id = ?, image = ?
            WHERE id = ?
        ");

        return $stmt->execute([
            $data['name'],
            $data['price'],
            $data['category_id'],
            $data['image'],
            $id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM products WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function updateAvailability($id, $status)
    {
        $stmt = $this->pdo->prepare("
            UPDATE products
            SET is_available = ?
            WHERE id = ?
        ");

        return $stmt->execute([$status, $id]);
    }
}