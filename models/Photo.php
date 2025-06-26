<?php
class Photo {
    private $db;
    public function __construct($db) {
        $this->db = $db;
    }
    public function getAll() {
        $stmt = $this->db->prepare('SELECT * FROM photos ORDER BY id DESC');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getByCategory($category_id) {
        $stmt = $this->db->prepare('SELECT * FROM photos WHERE category_id = :category_id ORDER BY id DESC');
        $stmt->execute(['category_id' => $category_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function create($category_id, $image_path) {
        $stmt = $this->db->prepare('INSERT INTO photos (category_id, image_path) VALUES (:category_id, :image_path)');
        return $stmt->execute(['category_id' => $category_id, 'image_path' => $image_path]);
    }
    public function delete($id) {
        $stmt = $this->db->prepare('DELETE FROM photos WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
} 