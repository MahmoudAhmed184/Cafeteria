<?php

class Database
{
    private static $host = "localhost";
    private static $dbname = "cafeteria";
    private static $username = "root";
    private static $password = "";

    public static function connect()
    {
        try {

           $pdo = new PDO(
    "mysql:host=127.0.0.1;dbname=" . self::$dbname,
    self::$username,
    self::$password
);

            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            return $pdo;

        } catch (PDOException $e) {

            die("Database connection failed: " . $e->getMessage());

        }
    }
}