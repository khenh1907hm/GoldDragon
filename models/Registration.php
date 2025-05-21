<?php
class Registration {
    private $conn;
    private $table = 'registrations';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create Registration
    public function create($data) {
        $query = "INSERT INTO " . $this->table . " 
                (student_name, nick_name, age, parent_name, phone, address, content) 
                VALUES 
                (:student_name, :nick_name, :age, :parent_name, :phone, :address, :content)";
        
        $stmt = $this->conn->prepare($query);
        
        // Clean data
        $student_name = htmlspecialchars(strip_tags($data['student_name']));
        $nick_name = htmlspecialchars(strip_tags($data['nick_name']));
        $age = htmlspecialchars(strip_tags($data['age']));
        $parent_name = htmlspecialchars(strip_tags($data['parent_name']));
        $phone = htmlspecialchars(strip_tags($data['phone']));
        $address = htmlspecialchars(strip_tags($data['address']));
        $content = htmlspecialchars(strip_tags($data['content']));

        // Bind data
        $stmt->bindParam(':student_name', $student_name);
        $stmt->bindParam(':nick_name', $nick_name);
        $stmt->bindParam(':age', $age);
        $stmt->bindParam(':parent_name', $parent_name);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':content', $content);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Read all registrations
    public function read() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Update registration status
    public function updateStatus($id, $status) {
        $query = "UPDATE " . $this->table . " SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        
        // Clean data
        $status = htmlspecialchars(strip_tags($status));
        
        // Bind data
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':status', $status);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Delete registration
    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Get total count of registrations
    public function count() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    // Get recent registrations
    public function getRecent($limit = 5) {
        $query = "SELECT * FROM " . $this->table . " ORDER BY created_at DESC LIMIT :limit";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
