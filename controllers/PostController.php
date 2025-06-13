<?php
require_once '../models/Post.php';
require_once __DIR__ . '/../includes/Database.php';

class PostController {
    private $post;    
    private $viewPath;
    private $adminPath;
    private $db;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        try {
            $this->db = Database::getInstance()->getConnection();
        } catch (Exception $e) {
            error_log("PostController Error: " . $e->getMessage());
            throw new Exception("Failed to initialize controller");
        }
        $this->post = new Post();
        $this->adminPath = dirname(__DIR__) . '/admin/';
        $this->viewPath = $this->adminPath . 'views/posts/';
    }

    // List all posts
    public function index() {
        $result = $this->post->getAll();
        $posts = $result->fetchAll(PDO::FETCH_ASSOC);
        require_once $this->viewPath . 'index.php';
    }

    // Show create form
    public function create() {
        require_once $this->viewPath . 'create.php';
    }

    // Store new post
    public function store($data, $files) {
        try {
            // Validate required fields
            if (empty($data['title']) || empty($data['content'])) {
                throw new Exception("Title and content are required");
            }

            // Set default status if not provided
            $data['status'] = $data['status'] ?? 'draft';

            // Store the post
            $result = $this->post->store($data);
            
            if (!$result) {
                throw new Exception("Failed to store post");
            }

            return true;
        } catch (Exception $e) {
            error_log("Store Post Error: " . $e->getMessage());
            return false;
        }
    }

    // Show edit form
    public function edit($id) {
        $post = $this->post->getById($id);
        require_once $this->viewPath . 'edit.php';
    }

    // Update post
    public function update($id, $data, $files) {
        try {
            // Validate required fields
            if (empty($data['title']) || empty($data['content'])) {
                throw new Exception("Title and content are required");
            }

            // Set default status if not provided
            $data['status'] = $data['status'] ?? 'draft';

            // Update the post
            $result = $this->post->update($id, $data);
            
            if (!$result) {
                throw new Exception("Failed to update post");
            }

            return true;
        } catch (Exception $e) {
            error_log("Update Post Error: " . $e->getMessage());
            return false;
        }
    }

    // Delete post
    public function delete($id) {
        try {
            $result = $this->post->delete($id);
            if (!$result) {
                throw new Exception("Failed to delete post");
            }
            return true;
        } catch (Exception $e) {
            error_log("Delete Post Error: " . $e->getMessage());
            return false;
        }
    }

    // Get total count of posts
    public function count() {
        return $this->post->count();
    }

    // Get recent posts
    public function getRecent($limit = 5) {
        return $this->post->getRecent($limit);
    }
}
