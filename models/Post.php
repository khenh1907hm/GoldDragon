<?php
require_once __DIR__ . '/../includes/Database.php';

class Post {
    private $db;
    private $perPage = 10; // Số bài viết trên mỗi trang

    public function __construct() {
        try {
            $this->db = Database::getInstance()->getConnection();
        } catch (Exception $e) {
            error_log("Post Model Error: " . $e->getMessage());
            throw new Exception("Failed to initialize model");
        }
    }

    // Get all posts with pagination
    public function getAll($page = 1) {
        try {
            $offset = ($page - 1) * $this->perPage;
            
            $sql = "SELECT * FROM posts ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':limit', $this->perPage, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt;
        } catch (Exception $e) {
            error_log("Get All Posts Error: " . $e->getMessage());
            return false;
        }
    }

    // Get post by ID
    public function getById($id) {
        try {
            $sql = "SELECT * FROM posts WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Get Post Error: " . $e->getMessage());
            return false;
        }
    }

    // Store new post
    public function store($data) {
        try {
            // Xử lý upload ảnh
            $imagePath = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../uploads/posts/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $fileName = uniqid() . '_' . basename($_FILES['image']['name']);
                $targetPath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                    $imagePath = 'uploads/posts/' . $fileName;
                }
            }

            $sql = "INSERT INTO posts (title, content, image, status, created_at) 
                    VALUES (:title, :content, :image, :status, NOW())";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':title', $data['title']);
            $stmt->bindParam(':content', $data['content']);
            $stmt->bindParam(':image', $imagePath);
            $stmt->bindParam(':status', $data['status']);
            
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Store Post Error: " . $e->getMessage());
            return false;
        }
    }

    // Update post
    public function update($id, $data) {
        try {
            // Xử lý upload ảnh mới nếu có
            $imagePath = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../uploads/posts/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $fileName = uniqid() . '_' . basename($_FILES['image']['name']);
                $targetPath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                    $imagePath = 'uploads/posts/' . $fileName;
                    
                    // Xóa ảnh cũ nếu có
                    $oldPost = $this->getById($id);
                    if ($oldPost && $oldPost['image']) {
                        $oldImagePath = __DIR__ . '/../' . $oldPost['image'];
                        if (file_exists($oldImagePath)) {
                            unlink($oldImagePath);
                        }
                    }
                }
            }

            $sql = "UPDATE posts 
                    SET title = :title, 
                        content = :content, 
                        status = :status";
            
            if ($imagePath) {
                $sql .= ", image = :image";
            }
            
            $sql .= " WHERE id = :id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':title', $data['title']);
            $stmt->bindParam(':content', $data['content']);
            $stmt->bindParam(':status', $data['status']);
            
            if ($imagePath) {
                $stmt->bindParam(':image', $imagePath);
            }
            
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Update Post Error: " . $e->getMessage());
            return false;
        }
    }

    // Delete post
    public function delete($id) {
        try {
            // Xóa ảnh nếu có
            $post = $this->getById($id);
            if ($post && $post['image']) {
                $imagePath = __DIR__ . '/../' . $post['image'];
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            $sql = "DELETE FROM posts WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Delete Post Error: " . $e->getMessage());
            return false;
        }
    }

    // Get total count of posts
    public function count() {
        try {
            $query = "SELECT COUNT(*) as total FROM posts";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['total'];
        } catch (Exception $e) {
            error_log("Count Posts Error: " . $e->getMessage());
            return 0;
        }
    }

    // Get total pages
    public function getTotalPages() {
        return ceil($this->count() / $this->perPage);
    }

    // Get recent posts
    public function getRecent($limit = 5) {
        try {
            $query = "SELECT * FROM posts ORDER BY created_at DESC LIMIT :limit";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Get Recent Posts Error: " . $e->getMessage());
            return [];
        }
    }
}
