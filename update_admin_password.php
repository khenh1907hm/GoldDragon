<?php
require_once 'config/database.php';

try {
    $database = Database::getInstance();
    $db = $database->connect();
    
    // Hash the password
    $password = 'admin';
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Update the password in database
    $query = "UPDATE users SET password = :password WHERE username = 'admin'";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':password', $hashed_password);
    
    if($stmt->execute()) {
        echo "Password updated successfully!";
    } else {
        echo "Failed to update password.";
    }
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
