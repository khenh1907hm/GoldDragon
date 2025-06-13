<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/api_error.log');

// Set headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Custom error handler
function handleError($errno, $errstr, $errfile, $errline) {
    error_log("Error [$errno] $errstr on line $errline in file $errfile");
    echo json_encode([
        'success' => false,
        'message' => 'Internal server error',
        'debug' => $errstr
    ]);
    exit();
}

// Custom exception handler
function handleException($e) {
    error_log("Exception: " . $e->getMessage() . "\n" . $e->getTraceAsString());
    echo json_encode([
        'success' => false,
        'message' => 'Internal server error',
        'debug' => $e->getMessage()
    ]);
    exit();
}

// Set error handlers
set_error_handler('handleError');
set_exception_handler('handleException');

try {
    // Get the request path
    $request_uri = $_SERVER['REQUEST_URI'];
    $base_path = '/RongVang/api/';
    $endpoint = str_replace($base_path, '', $request_uri);
    
    // Log the request
    error_log("API Request: " . $request_uri);
    error_log("POST data: " . print_r($_POST, true));

    // Route the request
    switch ($endpoint) {
        case 'register':
            require_once __DIR__ . '/../controllers/RegistrationController.php';
            $controller = new RegistrationController();
            $controller->register();
            break;
            
        default:
            throw new Exception('Invalid endpoint');
    }
} catch (Exception $e) {
    error_log("API Error: " . $e->getMessage() . "\n" . $e->getTraceAsString());
    echo json_encode([
        'success' => false,
        'message' => 'Internal server error',
        'debug' => $e->getMessage()
    ]);
}
