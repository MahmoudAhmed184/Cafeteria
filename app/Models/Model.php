<?php

require_once "../Database/Database.php";

abstract class Model {
    protected $table;
    protected $columns;

    private function __construct() {
        $this->table = $this->getTableName();
        $this->columns = self::getColumns();
    }

    public static function getColumns() {
        $instance = new static();
        $tableName = $instance->getTableName();
        $connection = $instance->getConnection();
        $result = $connection->query("DESCRIBE $tableName");
        $columns = [];
        while ($row = $result->fetch_assoc()) {
            $columns[] = $row['Field'];
        }
        return $columns;
    }

    public static function all() {
        $instance = new static();
        $tableName = $instance->getTableName();
        $connection = $instance->getConnection();
        $result = $connection->query("SELECT * FROM $tableName");
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    public static function find($id) {
        $instance = new static();
        $tableName = $instance->getTableName();
        $connection = $instance->getConnection();
        $result = $connection->query("SELECT * FROM $tableName WHERE id = $id");
        return $result->fetch_assoc();
    }

    public static function create($data) {
        $instance = new static();
        $tableName = $instance->getTableName();
        $connection = $instance->getConnection();
        $valid_columns = [];
        foreach ($instance->columns as $column) {
            if (isset($data[$column])) {
                $valid_columns[] = $column;
            }
        }
        $columns = implode(", ", $valid_columns);
        // value may number, string, or null, so we need to handle them accordingly
        $values = implode(", ", array_map(function($column) use ($data) {
            if (is_null($data[$column])) {
                return "NULL";
            } elseif (is_string($data[$column])) {
                return "'" . $connection->real_escape_string($data[$column]) . "'";
            } else {
                return $data[$column];
            }
        }, $valid_columns));
        
        $connection->query("INSERT INTO $tableName ($columns) VALUES ($values)");
    }

    public static function update($id, $data) {
        $instance = new static();
        $tableName = $instance->getTableName();
        $connection = $instance->getConnection();
        $valid_columns = [];
        foreach ($instance->columns as $column) {
            if (isset($data[$column])) {
                $valid_columns[] = "$column = " . (is_string($data[$column]) ? "'" . $connection->real_escape_string($data[$column]) . "'" : $data[$column]);
            }
        }
        $set_clause = implode(", ", $valid_columns);
        $connection->query("UPDATE $tableName SET $set_clause WHERE id = $id");
    }

    public static function delete($id) {
        $instance = new static();
        $tableName = $instance->getTableName();
        $connection = $instance->getConnection();
        $connection->query("DELETE FROM $tableName WHERE id = $id");
    }

    private function getTableName() {
        $className = get_class($this);
        $tableName = strtolower($className) . 's';
        return $tableName;
    }

    private function getConnection() {
        return Database::connect();
    }
}