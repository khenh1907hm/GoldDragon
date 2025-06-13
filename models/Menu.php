<?php
require_once __DIR__ . '/../includes/Database.php';

class Menu {
    private $db;

    public function __construct() {
        try {
            $this->db = Database::getInstance()->getConnection();
        } catch (Exception $e) {
            error_log("Menu Model Error: " . $e->getMessage());
            throw new Exception("Failed to initialize model");
        }
    }

    // Get all menus
    public function getAll() {
        try {
            $sql = "SELECT * FROM menus ORDER BY created_at DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt;
        } catch (Exception $e) {
            error_log("Get All Menus Error: " . $e->getMessage());
            return false;
        }
    }

    // Get menu by ID
    public function getById($id) {
        try {
            $sql = "SELECT * FROM menus WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Get Menu Error: " . $e->getMessage());
            return false;
        }
    }

    // Create new menu
    public function create($data) {
        try {
            $sql = "INSERT INTO menus (title, content, created_at) 
                    VALUES (:title, :content, NOW())";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':title', $data['title']);
            $stmt->bindParam(':content', $data['content']);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Create Menu Error: " . $e->getMessage());
            return false;
        }
    }

    // Update menu
    public function update($id, $data) {
        try {
            $sql = "UPDATE menus 
                    SET title = :title, content = :content 
                    WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':title', $data['title']);
            $stmt->bindParam(':content', $data['content']);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Update Menu Error: " . $e->getMessage());
            return false;
        }
    }

    // Delete menu
    public function delete($id) {
        try {
            $sql = "DELETE FROM menus WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Delete Menu Error: " . $e->getMessage());
            return false;
        }
    }
}
