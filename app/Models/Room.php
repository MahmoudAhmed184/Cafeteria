<?php

namespace App\Models;

use PDO;

class Room
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function fetchAll(): array
    {
        $statement = $this->connection->query('SELECT * FROM rooms ORDER BY room_number ASC');
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
