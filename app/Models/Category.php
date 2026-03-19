<?php

namespace App\Models;

use PDO;

class Category
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function fetchAll(): array
    {
        return $this->connection->query('SELECT * FROM categories ORDER BY name ASC')->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(string $name): int
    {
        $stmt = $this->connection->prepare('INSERT INTO categories (name) VALUES (:name)');
        $stmt->execute(['name' => $name]);
        return (int) $this->connection->lastInsertId();
    }
}
