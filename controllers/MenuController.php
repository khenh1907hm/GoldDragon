<?php
require_once '../models/Menu.php';
require_once '../config/database.php';

class MenuController {
    private $menu;    public function __construct() {
        $database = Database::getInstance();
        $db = $database->connect();
        $this->menu = new Menu($db);
    }

    // List all menus
    public function index() {
        $result = $this->menu->read();
        $menus = $result->fetchAll(PDO::FETCH_ASSOC);
        require_once '../views/menus/index.php';
    }

    // Show create form
    public function create() {
        require_once '../views/menus/create.php';
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
                header('Location: index.php?action=menus');
            } else {
                echo "Có lỗi xảy ra";
            }
        }
    }

    // Show edit form
    public function edit($id) {
        $menu = $this->menu->readOne($id);
        require_once '../views/menus/edit.php';
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
                header('Location: index.php?action=menus');
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
