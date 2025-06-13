<?php
require_once '../../config/database.php';
require_once '../../models/Student.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set headers for JSON response
header('Content-Type: application/json; charset=utf-8');

try {
    // Get search term
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    
    if (empty($search)) {
        echo json_encode(['students' => []], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // Initialize Student model
    $student = new Student();
    
    // Search students
    $result = $student->searchStudents($search);
    
    if ($result === false) {
        throw new Exception("Search operation failed");
    }
    
    $students = $result->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['students' => $students], JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    error_log("Search error: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());
    
    http_response_code(500);
    echo json_encode([
        'error' => 'An error occurred while searching students',
        'message' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ], JSON_UNESCAPED_UNICODE);
} 