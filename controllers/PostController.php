<?php
require_once '../models/Post.php';
require_once '../config/database.php';

class PostController {
    private $post;    
    private $viewPath;
    private $adminPath;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $database = Database::getInstance();
        $db = $database->connect();
        $this->post = new Post($db);
        $this->adminPath = dirname(__DIR__) . '/admin/';
        $this->viewPath = $this->adminPath . 'views/posts/';
    }

    // List all posts
    public function index() {

        $result = $this->post->read();
        $posts = $result->fetchAll(PDO::FETCH_ASSOC);
        ob_start();
        require $this->viewPath . 'index.php';
        // require_once dirname(__DIR__) . '/admin/views/posts/index.php';
        $content = ob_get_clean();
        $_SESSION['page_title'] = "Posts";
        require_once dirname(__DIR__) . '/admin/views/layout.php';
    }

    // Show create form
    public function create() {
        ob_start();
        require $this->viewPath . 'create.php';
        $content = ob_get_clean();
        $_SESSION['page_title'] = "Add New Post";
        require dirname(__DIR__) . '/admin/views/layout.php';
    }

    // Store new post
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'];
            $content = $_POST['content'];

            if ($this->post->create($title, $content)) {
                header('Location: index.php?action=posts');
            } else {
                echo "Có lỗi xảy ra";
            }
        }
    }

    // Show edit form
    public function edit($id) {
        $post = $this->post->readOne($id);
        // require_once '../views/posts/edit.php';
        require dirname(__DIR__) . '/admin/views/layout.php';
        require $this->viewPath . 'edit.php';
    }

    // Update post
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'];
            $content = $_POST['content'];

            if ($this->post->update($id, $title, $content)) {
                header('Location: index.php?page=posts');
            } else {
                echo "Có lỗi xảy ra";
            }
        }
    }

    // Delete post
    public function delete($id) {
        if ($this->post->delete($id)) {
            header('Location: index.php?action=posts');
        } else {
            echo "Có lỗi xảy ra";
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
