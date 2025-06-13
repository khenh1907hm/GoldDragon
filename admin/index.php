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
    switch ($page) {
        case 'dashboard':
            include 'views/dashboard.php';
            break;
        case 'posts':
            include 'views/posts.php';
            break;
        case 'menus':
            require_once __DIR__ . '/../models/Menu.php';
            include 'views/menus.php';
            break;
        case 'students':
            include 'views/students.php';
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