<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'rongvang';
    private $username = 'root';
    private $password = '';
    private $conn;
    private static $instance = null;

    private function __construct() {
        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4",
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
            error_log("Database connection successful");
        } catch (PDOException $e) {
            error_log("Database Connection Error: " . $e->getMessage());
            throw new Exception("Database connection failed");
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->conn;
    }

    public function prepare($sql) {
        try {
            return $this->conn->prepare($sql);
        } catch (PDOException $e) {
            error_log("Prepare Statement Error: " . $e->getMessage());
            throw new Exception("Failed to prepare statement");
        }
    }

    public function lastInsertId() {
        try {
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            error_log("Last Insert ID Error: " . $e->getMessage());
            throw new Exception("Failed to get last insert ID");
        }
    }

    public function beginTransaction() {
        try {
            return $this->conn->beginTransaction();
        } catch (PDOException $e) {
            error_log("Begin Transaction Error: " . $e->getMessage());
            throw new Exception("Failed to begin transaction");
        }
    }

    public function commit() {
        try {
            return $this->conn->commit();
        } catch (PDOException $e) {
            error_log("Commit Error: " . $e->getMessage());
            throw new Exception("Failed to commit transaction");
        }
    }

    public function rollBack() {
        try {
            return $this->conn->rollBack();
        } catch (PDOException $e) {
            error_log("Rollback Error: " . $e->getMessage());
            throw new Exception("Failed to rollback transaction");
        }
    }
} 