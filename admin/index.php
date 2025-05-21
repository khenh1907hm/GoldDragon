<?php
// Start the session at the very beginning
session_start();

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set the include path relative to admin directory
define('ROOT_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
set_include_path(get_include_path() . PATH_SEPARATOR . ROOT_PATH);

// Load controllers
require_once 'config/database.php';
require_once 'controllers/AuthController.php';
require_once 'controllers/PostController.php';
require_once 'controllers/MenuController.php';
require_once 'controllers/StudentController.php';
require_once 'controllers/RegistrationController.php';

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

    // Initialize other controllers after authentication
    $postController = new PostController();
    $menuController = new MenuController();
    $studentController = new StudentController();
    $registrationController = new RegistrationController();

    // Get the page and action from URL
    $page = $_GET['page'] ?? 'dashboard';
    $action = $_GET['action'] ?? 'index';    // Prepare data for dashboard
    if ($page === 'dashboard') {
        $totalPosts = method_exists($postController, 'count') ? $postController->count() : 0;
        $totalMenus = method_exists($menuController, 'count') ? $menuController->count() : 0;
        $totalStudents = method_exists($studentController, 'count') ? $studentController->count() : 0;
        $totalRegistrations = method_exists($registrationController, 'count') ? $registrationController->count() : 0;
        
        $recentPosts = $postController->getRecent(5);
        $recentRegistrations = $registrationController->getRecent(5);

        require_once 'views/dashboard.php';
    }
    // Route the request
    else {
        switch ($page) {
            case 'students':
                switch ($action) {
                    case 'index':
                        $studentController->index();
                        break;
                    case 'create':
                        $studentController->create();
                        break;
                    case 'store':
                        $studentController->store();
                        break;
                    case 'edit':
                        $studentController->edit($_GET['id']);
                        break;
                    case 'update':
                        $studentController->update($_GET['id']);
                        break;
                    case 'delete':
                        $studentController->delete($_GET['id']);
                        break;
                    default:
                        $studentController->index();
                }
                break;

            case 'posts':
                switch ($action) {
                    case 'index':
                        $postController->index();
                        break;
                    case 'create':
                        $postController->create();
                        break;
                    case 'store':
                        $postController->store();
                        break;
                    case 'edit':
                        $postController->edit($_GET['id']);
                        break;
                    case 'update':
                        $postController->update($_GET['id']);
                        break;
                    case 'delete':
                        $postController->delete($_GET['id']);
                        break;
                    default:
                        $postController->index();
                }
                break;

            case 'menus':
                switch ($action) {
                    case 'index':
                        $menuController->index();
                        break;
                    case 'create':
                        $menuController->create();
                        break;
                    case 'store':
                        $menuController->store();
                        break;
                    case 'edit':
                        $menuController->edit($_GET['id']);
                        break;
                    case 'update':
                        $menuController->update($_GET['id']);
                        break;
                    case 'delete':
                        $menuController->delete($_GET['id']);
                        break;
                    default:
                        $menuController->index();
                }
                break;

            default:
                require_once 'views/dashboard.php';
        }
    }
} catch (Exception $e) {
    // Log error
    error_log($e->getMessage());
    // Show error page
    $_SESSION['error'] = "An error occurred. Please try again later.";
    header('Location: error.php');
    exit();
}