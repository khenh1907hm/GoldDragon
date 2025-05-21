<?php
class User {
    private $conn;
    private $table = 'users';

    public $id;
    public $username;
    public $password;

    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function findByUsername($username) {
        $query = "SELECT * FROM " . $this->table . " WHERE username = :username LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {            $this->id = $row['id'];
            $this->username = $row['username'];
            $this->password = $row['password'];
            $this->created_at = $row['created_at'];
            return true;
        }
        return false;
    }    public function verifyPassword($password) {
        // Kiểm tra nếu password trong DB là plain text
        if ($password === $this->password) {
            // Nếu đúng password, update lại thành hash
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $query = "UPDATE " . $this->table . " SET password = :password WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':password', $hashed);
            $stmt->bindParam(':id', $this->id);
            $stmt->execute();
            return true;
        }
        // Nếu không, thử verify với hash
        return password_verify($password, $this->password);
    }

    public function create() {        $query = "INSERT INTO " . $this->table . " 
                 (username, password, created_at) 
                 VALUES (:username, :password, NOW())";

        $stmt = $this->conn->prepare($query);

        // Sanitize and hash password
        $this->username = htmlspecialchars(strip_tags($this->username));
        // $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);

        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':password', $this->password);
        // $stmt->bindParam(':email', $this->email);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
