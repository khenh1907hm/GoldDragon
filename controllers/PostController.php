<?php
require_once '../models/Post.php';
require_once '../config/database.php';

class PostController {
    private $post;    public function __construct() {
        $database = Database::getInstance();
        $db = $database->connect();
        $this->post = new Post($db);
    }

    // List all posts
    public function index() {
        $result = $this->post->read();
        $posts = $result->fetchAll(PDO::FETCH_ASSOC);
        require_once '../views/posts/index.php';
    }

    // Show create form
    public function create() {
        require_once '../views/posts/create.php';
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
        require_once '../views/posts/edit.php';
    }

    // Update post
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'];
            $content = $_POST['content'];

            if ($this->post->update($id, $title, $content)) {
                header('Location: index.php?action=posts');
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
