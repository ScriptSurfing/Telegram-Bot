<?php

namespace App\Services;

class DatabaseService
{
    private $conn;

    // Constructor to establish a database connection
    public function __construct()
    {
        $config = include __DIR__ . '/../../config/config.php';
        $this->conn = new \mysqli(
            $config['db']['host'],
            $config['db']['username'],
            $config['db']['password'],
            $config['db']['dbname']
        );

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    // Method to execute a prepared statement and return the result
    public function executeQuery($query, $params, $types)
    {
        $stmt = $this->conn->prepare($query);
        if ($params) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        return $stmt->get_result();
    }

    // Method to close the database connection
    public function close()
    {
        $this->conn->close();
    }
}