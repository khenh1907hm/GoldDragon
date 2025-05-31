<?php
require_once '../models/Menu.php';
require_once '../config/database.php';

class MenuController {
    private $menu;   
    private $viewPath;
    private $adminPath;


    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $database = Database::getInstance();
        $db = $database->connect();
        $this->menu = new Menu($db);
        $this->adminPath = dirname(__DIR__) . '/admin/';
        $this->viewPath = $this->adminPath . 'views/menu/';
    }

    // List all menus
    public function index() {
        $result = $this->menu->read();
        $menus = $result->fetchAll(PDO::FETCH_ASSOC);
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
                'week_label' => $_POST['week_label'],
                'monday' => $_POST['monday'],
                'tuesday' => $_POST['tuesday'],
                'wednesday' => $_POST['wednesday'],
                'thursday' => $_POST['thursday'],
                'friday' => $_POST['friday']
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
        $menu = $this->menu->readOne($id);
        require dirname(__DIR__) . '/admin/views/layout.php';
        require $this->viewPath . 'edit.php';
    }

    // Update menu
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $menuData = [
                'week_label' => $_POST['week_label'],
                'monday' => $_POST['monday'],
                'tuesday' => $_POST['tuesday'],
                'wednesday' => $_POST['wednesday'],
                'thursday' => $_POST['thursday'],
                'friday' => $_POST['friday']
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
            header('Location: index.php?action=menus');
        } else {
            echo "Có lỗi xảy ra";
        }
    }

    // Get total count of menu items
    public function count() {
        return $this->menu->count();
    }
}
