<?php
// Test file để kiểm tra hệ thống phân trang
require_once __DIR__ . '/../includes/Database.php';
require_once __DIR__ . '/../includes/Pagination.php';
require_once __DIR__ . '/../models/Registration.php';

try {
    // Khởi tạo database connection
    $db = Database::getInstance()->getConnection();
    
    // Khởi tạo model
    $registrationModel = new Registration($db);
    
    // Test pagination
    $currentPage = 1;
    $search = '';
    $status = '';
    
    echo "<h2>Test Pagination System</h2>";
    
    // Test count
    $totalCount = $registrationModel->count($search, $status);
    echo "<p>Total registrations: $totalCount</p>";
    
    // Test pagination object
    $pagination = $registrationModel->getPagination($currentPage, $search, $status);
    echo "<p>Total pages: " . $pagination->getTotalPages() . "</p>";
    echo "<p>Current page: " . $pagination->getCurrentPage() . "</p>";
    echo "<p>Items per page: " . $pagination->getLimit() . "</p>";
    
    // Test read with pagination
    $result = $registrationModel->read($currentPage, $search, $status);
    $registrations = $result ? $result->fetchAll(PDO::FETCH_ASSOC) : [];
    
    echo "<p>Registrations on current page: " . count($registrations) . "</p>";
    
    // Test statistics
    $stats = $registrationModel->getStats();
    echo "<h3>Statistics:</h3>";
    echo "<ul>";
    echo "<li>Total: " . $stats['total'] . "</li>";
    echo "<li>Pending: " . $stats['pending'] . "</li>";
    echo "<li>Approved: " . $stats['approved'] . "</li>";
    echo "<li>Today: " . $stats['today'] . "</li>";
    echo "</ul>";
    
    // Test pagination render
    echo "<h3>Pagination HTML:</h3>";
    echo $pagination->render([
        'showInfo' => true,
        'showFirstLast' => true,
        'showPrevNext' => true,
        'maxVisible' => 5,
        'alignment' => 'center'
    ]);
    
    echo "<h3>Sample Data:</h3>";
    if (!empty($registrations)) {
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Student Name</th><th>Parent Name</th><th>Status</th><th>Created At</th></tr>";
        foreach (array_slice($registrations, 0, 5) as $reg) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($reg['id']) . "</td>";
            echo "<td>" . htmlspecialchars($reg['student_name']) . "</td>";
            echo "<td>" . htmlspecialchars($reg['parent_name']) . "</td>";
            echo "<td>" . htmlspecialchars($reg['status']) . "</td>";
            echo "<td>" . htmlspecialchars($reg['created_at']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No registrations found.</p>";
    }
    
} catch (Exception $e) {
    echo "<h2>Error:</h2>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}
?> 