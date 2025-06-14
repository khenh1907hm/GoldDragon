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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Validate input
                if (empty($_POST['start_date']) || empty($_POST['end_date'])) {
                    throw new Exception('Vui lòng chọn tuần');
                }

                // Prepare data
                $data = [
                    'start_date' => $_POST['start_date'],
                    'end_date' => $_POST['end_date'],
                    'monday_breakfast' => $_POST['monday_breakfast'] ?? null,
                    'monday_lunch' => $_POST['monday_lunch'] ?? null,
                    'monday_snack' => $_POST['monday_snack'] ?? null,
                    'tuesday_breakfast' => $_POST['tuesday_breakfast'] ?? null,
                    'tuesday_lunch' => $_POST['tuesday_lunch'] ?? null,
                    'tuesday_snack' => $_POST['tuesday_snack'] ?? null,
                    'wednesday_breakfast' => $_POST['wednesday_breakfast'] ?? null,
                    'wednesday_lunch' => $_POST['wednesday_lunch'] ?? null,
                    'wednesday_snack' => $_POST['wednesday_snack'] ?? null,
                    'thursday_breakfast' => $_POST['thursday_breakfast'] ?? null,
                    'thursday_lunch' => $_POST['thursday_lunch'] ?? null,
                    'thursday_snack' => $_POST['thursday_snack'] ?? null,
                    'friday_breakfast' => $_POST['friday_breakfast'] ?? null,
                    'friday_lunch' => $_POST['friday_lunch'] ?? null,
                    'friday_snack' => $_POST['friday_snack'] ?? null
                ];

                // Check if menu already exists for this week
                $existingMenu = $this->menu->getByDateRange($data['start_date'], $data['end_date']);
                if ($existingMenu) {
                    throw new Exception('Thực đơn cho tuần này đã tồn tại');
                }

                // Create menu
                if ($this->menu->create($data)) {
                    $_SESSION['success'] = 'Thêm thực đơn thành công';
                } else {
                    throw new Exception('Không thể thêm thực đơn');
                }
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
            }
        }
        header('Location: index.php?page=menus');
        exit;
    }

    // Show edit form
    public function edit() {
        if (!isset($_GET['id'])) {
            $_SESSION['error'] = 'Không tìm thấy thực đơn';
            header('Location: index.php?page=menus');
            exit;
        }

        $menu = $this->menu->getById($_GET['id']);
        if (!$menu) {
            $_SESSION['error'] = 'Không tìm thấy thực đơn';
            header('Location: index.php?page=menus');
            exit;
        }

        require_once __DIR__ . '/../admin/views/edit_menu.php';
    }

    // Update menu
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                if (!isset($_POST['id'])) {
                    throw new Exception('Không tìm thấy thực đơn');
                }

                // Validate input
                if (empty($_POST['start_date']) || empty($_POST['end_date'])) {
                    throw new Exception('Vui lòng chọn tuần');
                }

                // Prepare data
                $data = [
                    'start_date' => $_POST['start_date'],
                    'end_date' => $_POST['end_date'],
                    'monday_breakfast' => $_POST['monday_breakfast'] ?? null,
                    'monday_lunch' => $_POST['monday_lunch'] ?? null,
                    'monday_snack' => $_POST['monday_snack'] ?? null,
                    'tuesday_breakfast' => $_POST['tuesday_breakfast'] ?? null,
                    'tuesday_lunch' => $_POST['tuesday_lunch'] ?? null,
                    'tuesday_snack' => $_POST['tuesday_snack'] ?? null,
                    'wednesday_breakfast' => $_POST['wednesday_breakfast'] ?? null,
                    'wednesday_lunch' => $_POST['wednesday_lunch'] ?? null,
                    'wednesday_snack' => $_POST['wednesday_snack'] ?? null,
                    'thursday_breakfast' => $_POST['thursday_breakfast'] ?? null,
                    'thursday_lunch' => $_POST['thursday_lunch'] ?? null,
                    'thursday_snack' => $_POST['thursday_snack'] ?? null,
                    'friday_breakfast' => $_POST['friday_breakfast'] ?? null,
                    'friday_lunch' => $_POST['friday_lunch'] ?? null,
                    'friday_snack' => $_POST['friday_snack'] ?? null
                ];

                // Check if menu already exists for this week (excluding current menu)
                $existingMenu = $this->menu->getByDateRange($data['start_date'], $data['end_date']);
                if ($existingMenu && $existingMenu['id'] != $_POST['id']) {
                    throw new Exception('Thực đơn cho tuần này đã tồn tại');
                }

                // Update menu
                if ($this->menu->update($_POST['id'], $data)) {
                    $_SESSION['success'] = 'Cập nhật thực đơn thành công';
                } else {
                    throw new Exception('Không thể cập nhật thực đơn');
                }
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
            }
        }
        header('Location: index.php?page=menus');
        exit;
    }

    // Delete menu
    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            try {
                if ($this->menu->delete($_POST['id'])) {
                    $_SESSION['success'] = 'Xóa thực đơn thành công';
                } else {
                    throw new Exception('Không thể xóa thực đơn');
                }
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
            }
        }
        header('Location: index.php?page=menus');
        exit;
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

    public function getAll() {
        return $this->menu->getAll();
    }

    public function getById($id) {
        return $this->menu->getById($id);
    }

    public function getCurrentWeekMenu() {
        return $this->menu->getCurrentWeekMenu();
    }

    public function getCurrentWeekInfo() {
        return $this->menu->getCurrentWeekInfo();
    }
}
