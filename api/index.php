<?php
// Default API response structure
header('Content-Type: application/json');

function sendResponse($status, $message, $data = null) {
    echo json_encode([
        'status' => $status,
        'message' => $message,
        'data' => $data
    ]);
    exit;
}

// Get request method and path
$method = $_SERVER['REQUEST_METHOD'];
$request = $_SERVER['REQUEST_URI'];

// API Routes
if (strpos($request, '/api/') === 0) {
    $path = substr($request, 5);
    
    switch ($path) {
        case 'register':
            if ($method === 'POST') {
                require_once '../controllers/RegistrationController.php';
                $controller = new RegistrationController();
                $controller->register();
            } else {
                sendResponse('error', 'Method not allowed', null);
            }
            break;
            
        case 'posts':
            require_once '../controllers/PostController.php';
            $controller = new PostController();
            
            switch ($method) {
                case 'GET':
                    $result = $controller->index();
                    sendResponse('success', 'Posts retrieved successfully', $result);
                    break;
                // Add other methods as needed
            }
            break;
            
        case 'menus':
            require_once '../controllers/MenuController.php';
            $controller = new MenuController();
            
            switch ($method) {
                case 'GET':
                    $result = $controller->index();
                    sendResponse('success', 'Menus retrieved successfully', $result);
                    break;
                // Add other methods as needed
            }
            break;
            
        default:
            sendResponse('error', 'Endpoint not found', null);
    }
} else {
    sendResponse('error', 'Invalid API request', null);
}
