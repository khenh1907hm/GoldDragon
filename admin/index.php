<?php
// Start the session at the very beginning
session_start();

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set the include path relative to admin directory
define('ROOT_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
set_include_path(get_include_path() . PATH_SEPARATOR . ROOT_PATH);

// Include required files
require_once __DIR__ . '/../includes/Database.php';
require_once __DIR__ . '/../models/Menu.php';
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/PostController.php';
require_once __DIR__ . '/../controllers/MenuController.php';
require_once __DIR__ . '/../controllers/StudentController.php';
require_once __DIR__ . '/../controllers/RegistrationController.php';

try {
    // Initialize auth controller
    $auth = new AuthController();

    // Handle logout
    if (isset($_GET['action']) && $_GET['action'] === 'logout') {
        $auth->logout();
        header('Location: login.php');
        exit();
    }

    // Check authentication
    if (!$auth->isLoggedIn()) {
        header('Location: login.php');
        exit();
    }

    // Initialize controllers
    $postController = new PostController();
    $menuController = new MenuController();
    $studentController = new StudentController();
    $registrationController = new RegistrationController();

    // Get the page and action from URL
    $page = $_GET['page'] ?? 'dashboard';
    $action = $_GET['action'] ?? 'index';
    $operation = $_GET['op'] ?? '';

    // Handle post operations
    if ($page === 'posts') {
        switch ($operation) {
            case 'store':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $result = $postController->store($_POST, $_FILES);
                    if ($result) {
                        $_SESSION['success'] = "Post created successfully!";
                    } else {
                        $_SESSION['error'] = "Failed to create post. Please try again.";
                    }
                    header('Location: index.php?page=posts');
                    exit();
                }
                break;

            case 'update':
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['id'])) {
                    $result = $postController->update($_GET['id'], $_POST, $_FILES);
                    if ($result) {
                        $_SESSION['success'] = "Post updated successfully!";
                    } else {
                        $_SESSION['error'] = "Failed to update post. Please try again.";
                    }
                    header('Location: index.php?page=posts');
                    exit();
                }
                break;

            case 'delete':
                if (isset($_GET['id'])) {
                    $result = $postController->delete($_GET['id']);
                    if ($result) {
                        $_SESSION['success'] = "Post deleted successfully!";
                    } else {
                        $_SESSION['error'] = "Failed to delete post. Please try again.";
                    }
                    header('Location: index.php?page=posts');
                    exit();
                }
                break;
        }
    }

    // Handle post actions
    if ($action === 'post_delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $postId = $_POST['id'] ?? null;
        if ($postId) {
            $result = $postController->delete($postId);
            if ($result) {
                $_SESSION['success'] = "Post deleted successfully!";
            } else {
                $_SESSION['error'] = "Failed to delete post. Please try again.";
            }
        }
        header('Location: index.php?page=posts');
        exit();
    }

    // Handle post status toggle
    if ($action === 'toggle_status' && isset($_GET['id'])) {
        $postId = $_GET['id'];
        $result = $postController->toggleStatus($postId);
        if ($result) {
            $_SESSION['success'] = "Post status updated successfully!";
        } else {
            $_SESSION['error'] = "Failed to update post status. Please try again.";
        }
        header('Location: index.php?page=posts');
        exit();
    }

    // Handle registration operations
    if ($action === 'registrations' && $operation === 'update-status' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $registrationId = $_POST['id'] ?? null;
        $status = $_POST['status'] ?? 'approved';
        
        if ($registrationId) {
            // Sử dụng model Registration thay vì controller
            require_once __DIR__ . '/../models/Registration.php';
            $db = Database::getInstance()->getConnection();
            $registrationModel = new Registration($db);
            
            $result = $registrationModel->updateStatus($registrationId, $status);
            if ($result) {
                $_SESSION['success'] = "Registration status updated successfully!";
            } else {
                $_SESSION['error'] = "Failed to update registration status. Please try again.";
            }
        }
        header('Location: index.php?page=registrations');
        exit();
    }

    // Set page title
    $_SESSION['page_title'] = ucfirst($page);

    // Initialize variables for dashboard
    $totalPosts = method_exists($postController, 'count') ? $postController->count() : 0;
    $totalMenus = method_exists($menuController, 'count') ? $menuController->count() : 0;
    $totalStudents = method_exists($studentController, 'count') ? $studentController->count() : 0;
    $totalRegistrations = method_exists($registrationController, 'count') ? $registrationController->count() : 0;
    $recentPosts = method_exists($postController, 'getRecent') ? $postController->getRecent(5) : [];
    $recentRegistrations = method_exists($registrationController, 'getRecent') ? $registrationController->getRecent(5) : [];

    // Get content based on page
    ob_start();
    
    // Split the page parameter to handle sub-pages
    $pageParts = explode('/', $page);
    $mainPage = $pageParts[0];
    $subPage = $pageParts[1] ?? null;

    switch ($mainPage) {
        case 'dashboard':
            include 'views/dashboard.php';
            break;
        case 'posts':
            include 'views/posts.php';
            break;
        case 'menus':
            switch ($action) {
                case 'create':
                    $menuController->create();
                    break;
                case 'update':
                    $menuController->update();
                    break;
                case 'delete':
                    $menuController->delete();
                    break;
                case 'edit':
                    require_once __DIR__ . '/views/edit_menu.php';
                    break;
                case 'check':
                    // AJAX action to check if menu exists for a week
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        header('Content-Type: application/json');
                        $startDate = $_POST['start_date'] ?? '';
                        $endDate = $_POST['end_date'] ?? '';
                        
                        if (!empty($startDate) && !empty($endDate)) {
                            // Sử dụng model Menu trực tiếp
                            require_once __DIR__ . '/../models/Menu.php';
                            $menuModel = new Menu();
                            $menu = $menuModel->getByDateRange($startDate, $endDate);
                            if ($menu) {
                                echo json_encode([
                                    'exists' => true,
                                    'menu' => $menu
                                ]);
                            } else {
                                echo json_encode([
                                    'exists' => false,
                                    'menu' => null
                                ]);
                            }
                        } else {
                            echo json_encode([
                                'exists' => false,
                                'menu' => null
                            ]);
                        }
                        exit();
                    }
                    break;
                case 'get':
                    // AJAX action to get menu by ID
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        header('Content-Type: application/json');
                        $menuId = $_POST['id'] ?? '';
                        
                        if (!empty($menuId)) {
                            // Sử dụng model Menu trực tiếp
                            require_once __DIR__ . '/../models/Menu.php';
                            $menuModel = new Menu();
                            $menu = $menuModel->getById($menuId);
                            if ($menu) {
                                echo json_encode([
                                    'success' => true,
                                    'menu' => $menu
                                ]);
                            } else {
                                echo json_encode([
                                    'success' => false,
                                    'message' => 'Menu not found'
                                ]);
                            }
                        } else {
                            echo json_encode([
                                'success' => false,
                                'message' => 'Invalid menu ID'
                            ]);
                        }
                        exit();
                    }
                    break;
                default:
                    // Lấy danh sách thực đơn
                    $menus = $menuController->getAll();
                    require_once __DIR__ . '/views/menus.php';
                    break;
            }
            break;
        case 'students':
            if ($subPage === 'create') {
                include 'views/students/create.php';
            } elseif ($subPage === 'edit') {
                if (isset($_GET['id'])) {
                    $studentController->edit($_GET['id']);
                } else {
                    $_SESSION['error'] = "ID học sinh không hợp lệ!";
                    header('Location: index.php?page=students');
                    exit();
                }
            } elseif ($subPage === 'store') {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $result = $studentController->store($_POST);
                    if ($result) {
                        $_SESSION['success'] = "Học sinh đã được thêm thành công!";
                    } else {
                        $_SESSION['error'] = "Không thể thêm học sinh. Vui lòng thử lại.";
                    }
                    header('Location: index.php?page=students');
                    exit();
                }
            } elseif ($subPage === 'update') {
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['id'])) {
                    $result = $studentController->update($_GET['id'], $_POST);
                    if ($result) {
                        $_SESSION['success'] = "Thông tin học sinh đã được cập nhật thành công!";
                    } else {
                        $_SESSION['error'] = "Không thể cập nhật thông tin học sinh. Vui lòng thử lại.";
                    }
                    header('Location: index.php?page=students');
                    exit();
                }
            } else {
                include 'views/students.php';
            }
            break;
        case 'registrations':
            include 'views/registrations.php';
            break;
        default:
            include 'views/dashboard.php';
    }
    $content = ob_get_clean();

    // Include layout
    include 'views/layout.php';

} catch (Exception $e) {
    // Log error
    error_log($e->getMessage());
    // Show error page
    $_SESSION['error'] = "An error occurred. Please try again later.";
    header('Location: error.php');
    exit();
}