<?php

namespace App\Models;

use PDO;

class User
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function findByEmail(string $email): array|false
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM users WHERE email = :email AND is_active = 1 LIMIT 1'
        );
        $statement->execute(['email' => $email]);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): array|false
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM users WHERE id = :id AND is_active = 1 LIMIT 1'
        );
        $statement->execute(['id' => $id]);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function fetchAll(): array
    {
        $statement = $this->connection->query(
            'SELECT * FROM users WHERE is_active = 1 ORDER BY name ASC'
        );
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert(array $attributes): int
    {
        $statement = $this->connection->prepare(
            'INSERT INTO users (name, email, password, room_no, ext, profile_pic) 
             VALUES (:name, :email, :password, :room_no, :ext, :profile_pic)'
        );
        
        $statement->execute([
            'name' => $attributes['name'],
            'email' => $attributes['email'],
            'password' => $attributes['password'],
            'room_no' => $attributes['room_no'],
            'ext' => $attributes['ext'],
            'profile_pic' => $attributes['profile_pic'] ?? null,
        ]);
        
        return (int) $this->connection->lastInsertId();
    }

    public function update(int $id, array $attributes): bool
    {
        $sql = 'UPDATE users SET name = :name, email = :email, room_no = :room_no, ext = :ext';
        $parameters = [
            'name' => $attributes['name'],
            'email' => $attributes['email'],
            'room_no' => $attributes['room_no'],
            'ext' => $attributes['ext'],
            'id' => $id
        ];

        if (isset($attributes['password'])) {
            $sql .= ', password = :password';
            $parameters['password'] = $attributes['password'];
        }

        if (array_key_exists('profile_pic', $attributes)) {
            $sql .= ', profile_pic = :profile_pic';
            $parameters['profile_pic'] = $attributes['profile_pic'];
        }

        $sql .= ' WHERE id = :id AND is_active = 1';

        $statement = $this->connection->prepare($sql);
        return $statement->execute($parameters);
    }

    public function deactivate(int $id): bool
    {
        $statement = $this->connection->prepare(
            'UPDATE users SET is_active = 0 WHERE id = :id'
        );
        return $statement->execute(['id' => $id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->connection->prepare('DELETE FROM users WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }

    public function getOrderCount(int $id): int
    {
        $stmt = $this->connection->prepare('SELECT COUNT(*) FROM orders WHERE user_id = :id');
        $stmt->execute(['id' => $id]);
        return (int) $stmt->fetchColumn();
    }


    public function searchActiveUsers(string $term): array
    {
        $query = '%' . $term . '%';
        $sql = 'SELECT id, name, email, room_no, ext
                FROM users
                WHERE is_active = 1
                  AND role_id = 2
                  AND (name LIKE ? OR email LIKE ?)
                ORDER BY name ASC
                LIMIT 20';
        $statement = $this->connection->prepare($sql);
        $statement->execute([$query, $query]);

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function isActiveUser(int $userId): bool
    {
        $statement = $this->connection->prepare(
            'SELECT id FROM users WHERE id = :user_id AND is_active = 1 AND role_id = 2 LIMIT 1'
        );
        $statement->execute(['user_id' => $userId]);

        return (bool) $statement->fetchColumn();
    }

}


