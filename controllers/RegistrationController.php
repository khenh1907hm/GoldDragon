<?php
require_once '../models/Registration.php';
require_once '../config/database.php';

class RegistrationController {
    private $registration;    public function __construct() {
        $database = Database::getInstance();
        $db = $database->connect();
        $this->registration = new Registration($db);
    }

    // List all registrations
    public function index() {
        $result = $this->registration->read();
        $registrations = $result->fetchAll(PDO::FETCH_ASSOC);
        require_once '../views/registrations/index.php';
    }

    // Handle new registration from front-end
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $registrationData = [
                'student_name' => $_POST['student_name'],
                'nick_name' => $_POST['nick_name'],
                'age' => $_POST['age'],
                'parent_name' => $_POST['parent_name'],
                'phone' => $_POST['phone'],
                'address' => $_POST['address'],
                'content' => $_POST['content']
            ];

            if ($this->registration->create($registrationData)) {
                echo json_encode(['status' => 'success', 'message' => 'Đăng ký thành công']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Có lỗi xảy ra']);
            }
        }
    }

    // Update registration status
    public function updateStatus() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $status = $_POST['status'];

            if ($this->registration->updateStatus($id, $status)) {
                header('Location: index.php?action=registrations');
            } else {
                echo "Có lỗi xảy ra";
            }
        }
    }

    // Delete registration
    public function delete($id) {
        if ($this->registration->delete($id)) {
            header('Location: index.php?action=registrations');
        } else {
            echo "Có lỗi xảy ra";
        }
    }

    // Get total count of registrations
    public function count() {
        return $this->registration->count();
    }

    // Get recent registrations
    public function getRecent($limit = 5) {
        return $this->registration->getRecent($limit);
    }
}
