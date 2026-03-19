<?php

require_once __DIR__ . '/../config/database.php';

class Category
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::connect();
    }

    public function getAll()
    {
        $stmt = $this->pdo->query("SELECT * FROM categories");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}