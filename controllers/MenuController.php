<?php
require_once '../models/Menu.php';
require_once __DIR__ . '/../includes/Database.php';

class MenuController {
    private $menu;   
    private $viewPath;
    private $adminPath;
    private $db;


    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        try {
            $this->db = Database::getInstance()->getConnection();
            $this->menu = new Menu();
        } catch (Exception $e) {
            error_log("MenuController Error: " . $e->getMessage());
            throw new Exception("Failed to initialize controller");
        }
        $this->adminPath = dirname(__DIR__) . '/admin/';
        $this->viewPath = $this->adminPath . 'views/menu/';
    }

    // List all menus
    public function index() {
        $menus = $this->menu->getAll();
        ob_start();
        require $this->viewPath . 'index.php';
        $content = ob_get_clean();
        $_SESSION['page_title'] = "Menus";
        require dirname(__DIR__) . '/admin/views/layout.php';
    }

    // Show create form
    public function create() {
        require dirname(__DIR__) . '/admin/views/layout.php';
        require $this->viewPath . 'create.php';
        
        // require_once '../views/menus/create.php';
    }

    // Store new menu
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $menuData = [
                'title' => $_POST['title'],
                'content' => $_POST['content']
            ];

            if ($this->menu->create($menuData)) {
                header('Location: index.php?page=menus');
            } else {
                echo "Có lỗi xảy ra";
            }
        }
    }

    // Show edit form
    public function edit($id) {
        $menu = $this->menu->getById($id);
        require dirname(__DIR__) . '/admin/views/layout.php';
        require $this->viewPath . 'edit.php';
    }

    // Update menu
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $menuData = [
                'title' => $_POST['title'],
                'content' => $_POST['content']
            ];

            if ($this->menu->update($id, $menuData)) {
                header('Location: index.php?page=menus');
            } else {
                echo "Có lỗi xảy ra";
            }
        }
    }

    // Delete menu
    public function delete($id) {
        if ($this->menu->delete($id)) {
            header('Location: index.php?page=menus');
        } else {
            echo "Có lỗi xảy ra";
        }
    }

    // Get total count of menu items
    public function count() {
        try {
            $sql = "SELECT COUNT(*) as total FROM menus";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch (Exception $e) {
            error_log("Count Error: " . $e->getMessage());
            return 0;
        }
    }

    // Get recent menus
    public function getRecent($limit = 5) {
        try {
            $sql = "SELECT * FROM menus ORDER BY created_at DESC LIMIT :limit";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Get Recent Error: " . $e->getMessage());
            return [];
        }
    }
}
