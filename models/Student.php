<?php
class Student {
    private $conn;
    private $table = 'students';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get all students
    public function getAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Create Student
    public function create($data) {
        $query = "INSERT INTO " . $this->table . " 
                (full_name, nick_name, age, parent_name, phone, address, notes) 
                VALUES 
                (:full_name, :nick_name, :age, :parent_name, :phone, :address, :notes)";
        
        $stmt = $this->conn->prepare($query);
        
        // Clean data
        $full_name = htmlspecialchars(strip_tags($data['full_name']));
        $nick_name = htmlspecialchars(strip_tags($data['nick_name']));
        $age = htmlspecialchars(strip_tags($data['age']));
        $parent_name = htmlspecialchars(strip_tags($data['parent_name']));
        $phone = htmlspecialchars(strip_tags($data['phone']));
        $address = htmlspecialchars(strip_tags($data['address']));
        $notes = htmlspecialchars(strip_tags($data['notes']));

        // Bind data
        $stmt->bindParam(':full_name', $full_name);
        $stmt->bindParam(':nick_name', $nick_name);
        $stmt->bindParam(':age', $age);
        $stmt->bindParam(':parent_name', $parent_name);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':notes', $notes);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Read single student
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update student
    public function update($id, $data) {
        $query = "UPDATE " . $this->table . " 
                SET full_name = :full_name,
                    nick_name = :nick_name,
                    age = :age,
                    parent_name = :parent_name,
                    phone = :phone,
                    address = :address,
                    notes = :notes
                WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        
        // Clean data
        $full_name = htmlspecialchars(strip_tags($data['full_name']));
        $nick_name = htmlspecialchars(strip_tags($data['nick_name']));
        $age = htmlspecialchars(strip_tags($data['age']));
        $parent_name = htmlspecialchars(strip_tags($data['parent_name']));
        $phone = htmlspecialchars(strip_tags($data['phone']));
        $address = htmlspecialchars(strip_tags($data['address']));
        $notes = htmlspecialchars(strip_tags($data['notes']));

        // Bind data
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':full_name', $full_name);
        $stmt->bindParam(':nick_name', $nick_name);
        $stmt->bindParam(':age', $age);
        $stmt->bindParam(':parent_name', $parent_name);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':notes', $notes);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Delete student
    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Count total students
    public function count() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }
}
