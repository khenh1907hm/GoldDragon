<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'rongvang';
    private $username = 'root';
    private $password = '';
    private $conn;
    private static $instance = null;

    private function __construct() {}

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function connect() {
        if ($this->conn !== null) {
            return $this->conn;
        }

        try {
            $dsn = sprintf("mysql:host=%s;dbname=%s;charset=utf8", $this->host, $this->db_name);
            $this->conn = new PDO($dsn, $this->username, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]);
            return $this->conn;
        } catch(PDOException $e) {
            // Log the error
            error_log("Database Connection Error: " . $e->getMessage());
            throw new Exception("Database connection failed. Please try again later.");
        }
    }
}
