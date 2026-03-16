<?php

/**
 * @example `Database::connect()` to establish a connection to the database.
 * @example `$db = Database::getInstance()` to get the singleton instance of the Database class.
 * @example `$db->query($sql)` to execute a SQL query on the databases
 */
class Database
{
    private $host;
    private $username;
    private $password;
    private $database;
    private $connection;

    private static $instance;

    private function __construct($host, $username, $password, $database)
    {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
    }

    public static function getInstance($host = 'localhost', $username = 'root', $password = '', $database = '')
    {
        if (self::$instance === null) {
            self::$instance = new Database($host, $username, $password, $database);
        }
        return self::$instance;
    }

    public static function connect()
    {
        $instance = self::getInstance();
        $instance->connection = new mysqli($instance->host, $instance->username, $instance->password, $instance->database);
        if ($instance->connection->connect_error) {
            die("Connection failed: " . $instance->connection->connect_error);
        }
        return $instance;
    }

    public function query($sql)
    {
        return $this->connection->query($sql);
    }

    /**
     * Get the raw mysqli connection object
     * Used by Models and Services that need to call
     * real_escape_string(), begin_transaction(), etc.
     */
    public function getConnectionInstance()
    {
        return $this->connection;
    }

    public function __destruct()
    {
        if ($this->connection) {
            $this->connection->close();
        }
    }
}
