<?php
class PhotoCategory {
    private $db;
    public function __construct($db) {
        $this->db = $db;
    }
    public function getAll() {
        $stmt = $this->db->prepare('SELECT * FROM photo_categories ORDER BY id DESC');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function create($name) {
        $stmt = $this->db->prepare('INSERT INTO photo_categories (name) VALUES (:name)');
        return $stmt->execute(['name' => $name]);
    }
    public function delete($id) {
        $stmt = $this->db->prepare('DELETE FROM photo_categories WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
} 