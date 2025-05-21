<?php
class Menu {
    private $conn;
    private $table = 'menus';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create Menu
    public function create($data) {
        $query = "INSERT INTO " . $this->table . " 
                (week_label, monday, tuesday, wednesday, thursday, friday) 
                VALUES 
                (:week_label, :monday, :tuesday, :wednesday, :thursday, :friday)";
        
        $stmt = $this->conn->prepare($query);
        
        // Clean data
        $week_label = htmlspecialchars(strip_tags($data['week_label']));
        $monday = htmlspecialchars(strip_tags($data['monday']));
        $tuesday = htmlspecialchars(strip_tags($data['tuesday']));
        $wednesday = htmlspecialchars(strip_tags($data['wednesday']));
        $thursday = htmlspecialchars(strip_tags($data['thursday']));
        $friday = htmlspecialchars(strip_tags($data['friday']));

        // Bind data
        $stmt->bindParam(':week_label', $week_label);
        $stmt->bindParam(':monday', $monday);
        $stmt->bindParam(':tuesday', $tuesday);
        $stmt->bindParam(':wednesday', $wednesday);
        $stmt->bindParam(':thursday', $thursday);
        $stmt->bindParam(':friday', $friday);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Read all menus
    public function read() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Read single menu
    public function readOne($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update menu
    public function update($id, $data) {
        $query = "UPDATE " . $this->table . " 
                SET week_label = :week_label,
                    monday = :monday,
                    tuesday = :tuesday,
                    wednesday = :wednesday,
                    thursday = :thursday,
                    friday = :friday
                WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        
        // Clean data
        $week_label = htmlspecialchars(strip_tags($data['week_label']));
        $monday = htmlspecialchars(strip_tags($data['monday']));
        $tuesday = htmlspecialchars(strip_tags($data['tuesday']));
        $wednesday = htmlspecialchars(strip_tags($data['wednesday']));
        $thursday = htmlspecialchars(strip_tags($data['thursday']));
        $friday = htmlspecialchars(strip_tags($data['friday']));

        // Bind data
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':week_label', $week_label);
        $stmt->bindParam(':monday', $monday);
        $stmt->bindParam(':tuesday', $tuesday);
        $stmt->bindParam(':wednesday', $wednesday);
        $stmt->bindParam(':thursday', $thursday);
        $stmt->bindParam(':friday', $friday);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Delete menu
    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Get total count of menu items
    public function count() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }
}
