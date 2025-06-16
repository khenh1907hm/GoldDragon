<?php
require_once '../../includes/Database.php';
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

    // Initialize database connection
    try {
        $database = Database::getInstance();
        $db = $database->getConnection();
        
        // Test database connection
        if (!$database->testConnection()) {
            throw new Exception("Database connection test failed");
        }
        
        // Test if students table exists and has data
        $testStmt = $db->query("SELECT COUNT(*) as total FROM students");
        $totalStudents = $testStmt->fetch(PDO::FETCH_ASSOC)['total'];
        error_log("Total students in database: " . $totalStudents);
        
        if ($totalStudents === false) {
            throw new Exception("Failed to count students");
        }
    } catch (Exception $e) {
        error_log("Database connection error: " . $e->getMessage());
        throw new Exception("Database connection failed: " . $e->getMessage());
    }

    // Initialize Student model
    $student = new Student();
    
    // Search students
    $students = $student->searchStudents($search);
    
    if ($students === false) {
        throw new Exception("Search operation failed");
    }
    
    // Ensure we always return an array
    if (!is_array($students)) {
        $students = [];
    }
    
    // Log search results for debugging
    error_log("Search term: " . $search);
    error_log("Number of results: " . count($students));
    
    echo json_encode(['students' => $students], JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    error_log("Search error: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());
    
    http_response_code(500);
    echo json_encode([
        'error' => true,
        'message' => $e->getMessage(),
        'students' => [] // Always return an empty array for students
    ], JSON_UNESCAPED_UNICODE);
} 