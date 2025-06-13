<?php
// Bật hiển thị lỗi để debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    require_once '../config/database.php';
    require_once '../models/Student.php';

    $database = new Database();
    $db = $database->connect();

    if (isset($_GET['keyword'])) {
        $keyword = $_GET['keyword'];
        
        // Debug: Log keyword
        error_log("Search keyword: " . $keyword);
        
        $studentModel = new Student($db);
        $students = $studentModel->searchStudents($keyword);
        
        // Debug: Log số lượng kết quả
        error_log("Found students: " . count($students));
        
        header('Content-Type: application/json');
        echo json_encode($students);
    } else {
        // Trả về lỗi nếu không có keyword
        header('Content-Type: application/json');
        echo json_encode(['error' => 'No keyword provided']);
    }
} catch (Exception $e) {
    // Debug: Log lỗi
    error_log("Error in search_students.php: " . $e->getMessage());
    
    header('Content-Type: application/json');
    echo json_encode(['error' => $e->getMessage()]);
}