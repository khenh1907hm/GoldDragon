<?php
class Post {
    private $conn;
    private $table = 'posts';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create Post
    public function create($title, $content) {
        $query = "INSERT INTO " . $this->table . " (title, content) VALUES (:title, :content)";
        $stmt = $this->conn->prepare($query);
        
        // Clean data
        $title = htmlspecialchars(strip_tags($title));
        $content = htmlspecialchars(strip_tags($content));

        // Bind data
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Read all posts
    public function read() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Read single post
    public function readOne($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update post
    public function update($id, $title, $content) {
        $query = "UPDATE " . $this->table . " 
                SET title = :title, content = :content 
                WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        
        // Clean data
        $title = htmlspecialchars(strip_tags($title));
        $content = htmlspecialchars(strip_tags($content));
        
        // Bind data
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Delete post
    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Get total count of posts
    public function count() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    // Get recent posts
    public function getRecent($limit = 5) {
        $query = "SELECT * FROM " . $this->table . " ORDER BY created_at DESC LIMIT :limit";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
