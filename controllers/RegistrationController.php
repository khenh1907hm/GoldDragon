<?php
require_once __DIR__ . '/../includes/Database.php';
require_once __DIR__ . '/../includes/Mailer.php';

class RegistrationController {
    private $db;
    private $mailer;

    public function __construct() {
        try {
            $this->db = Database::getInstance()->getConnection();
            $this->mailer = new Mailer();
        } catch (Exception $e) {
            error_log("RegistrationController Error: " . $e->getMessage());
            throw new Exception("Failed to initialize controller");
        }
    }

    // List all registrations
    public function index() {
        $registrations = $this->getAll();
        require_once '../views/registrations/index.php';
    }

    // Handle new registration from front-end
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Debug: Log received data
                error_log('Received POST data: ' . print_r($_POST, true));
                
                // Get form data
                $registrationData = [
                    'student_name' => $_POST['student_name'] ?? '',
                    'nick_name' => $_POST['nick_name'] ?? '',
                    'age' => $_POST['age'] ?? '',
                    'parent_name' => $_POST['parent_name'] ?? '',
                    'phone' => $_POST['phone'] ?? '',
                    'address' => $_POST['address'] ?? '',
                    'content' => $_POST['content'] ?? ''
                ];

                // Validate required fields
                $requiredFields = ['student_name', 'nick_name', 'age', 'parent_name', 'phone', 'address', 'content'];
                $missingFields = [];
                foreach ($requiredFields as $field) {
                    if (empty($registrationData[$field])) {
                        $missingFields[] = $field;
                    }
                }

                if (!empty($missingFields)) {
                    throw new Exception("Missing required fields: " . implode(', ', $missingFields));
                }

                // Validate phone number
                if (!preg_match('/^[0-9]{10}$/', $registrationData['phone'])) {
                    throw new Exception("Invalid phone number format");
                }

                // Validate age
                if (!is_numeric($registrationData['age']) || $registrationData['age'] < 0 || $registrationData['age'] > 6) {
                    throw new Exception("Age must be between 0 and 6");
                }

                // Begin transaction
                $this->db->beginTransaction();

                try {
                    $sql = "INSERT INTO registrations (parent_name, student_name, nick_name, age, phone, address, content, status, created_at) 
                            VALUES (:parent_name, :student_name, :nick_name, :age, :phone, :address, :content, 'pending', NOW())";
                    
                    $stmt = $this->db->prepare($sql);
                    $stmt->bindParam(':parent_name', $registrationData['parent_name']);
                    $stmt->bindParam(':student_name', $registrationData['student_name']);
                    $stmt->bindParam(':nick_name', $registrationData['nick_name']);
                    $stmt->bindParam(':age', $registrationData['age']);
                    $stmt->bindParam(':phone', $registrationData['phone']);
                    $stmt->bindParam(':address', $registrationData['address']);
                    $stmt->bindParam(':content', $registrationData['content']);
                    
                    if (!$stmt->execute()) {
                        throw new Exception("Failed to save registration");
                    }

                    // Get the new registration ID
                    $registrationId = $this->db->lastInsertId();
                    
                    // Get the full registration data
                    $registration = $this->getById($registrationId);
                    
                    if (!$registration) {
                        throw new Exception("Failed to retrieve registration data");
                    }

                    // Commit transaction
                    $this->db->commit();

                    // Try to send email notification
                    try {
                        $this->mailer->sendNewRegistrationNotification($registration);
                    } catch (Exception $e) {
                        // Log email error but don't fail the registration
                        error_log("Email sending failed: " . $e->getMessage());
                    }
                    
                    echo json_encode([
                        'success' => true,
                        'message' => 'Đăng ký thành công! Chúng tôi sẽ liên hệ với bạn sớm nhất.'
                    ]);
                } catch (Exception $e) {
                    // Rollback transaction on error
                    $this->db->rollBack();
                    throw $e;
                }
            } catch (Exception $e) {
                error_log("Registration Error: " . $e->getMessage());
                echo json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            }
            exit();
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
            exit();
        }
    }

    // Update registration status
    public function updateStatus() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $status = $_POST['status'] ?? null;

            if (!$id || !$status) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Missing required parameters'
                ]);
                exit();
            }

            try {
                $sql = "UPDATE registrations SET status = :status WHERE id = :id";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':id', $id);
                $stmt->bindParam(':status', $status);
                
                if ($stmt->execute()) {
                    echo json_encode([
                        'success' => true,
                        'message' => 'Status updated successfully'
                    ]);
                } else {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Failed to update status'
                    ]);
                }
            } catch (Exception $e) {
                error_log("Update Status Error: " . $e->getMessage());
                echo json_encode([
                    'success' => false,
                    'message' => 'An error occurred while updating status'
                ]);
            }
            exit();
        }
    }

    // Delete registration
    public function deleteRegistration() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;

            if (!$id) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Missing registration ID'
                ]);
                exit();
            }

            try {
                $sql = "DELETE FROM registrations WHERE id = :id";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':id', $id);
                
                if ($stmt->execute()) {
                    echo json_encode([
                        'success' => true,
                        'message' => 'Registration deleted successfully'
                    ]);
                } else {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Failed to delete registration'
                    ]);
                }
            } catch (Exception $e) {
                error_log("Delete Registration Error: " . $e->getMessage());
                echo json_encode([
                    'success' => false,
                    'message' => 'An error occurred while deleting registration'
                ]);
            }
            exit();
        }
    }

    // Get total count of registrations
    public function count() {
        try {
            $sql = "SELECT COUNT(*) as total FROM registrations";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch (Exception $e) {
            error_log("Count Error: " . $e->getMessage());
            return 0;
        }
    }

    // Get recent registrations
    public function getRecent($limit = 5) {
        try {
            $sql = "SELECT * FROM registrations ORDER BY created_at DESC LIMIT :limit";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Get Recent Error: " . $e->getMessage());
            return [];
        }
    }

    // Get all registrations
    public function getAll() {
        try {
            $sql = "SELECT * FROM registrations ORDER BY created_at DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Get All Registrations Error: " . $e->getMessage());
            return [];
        }
    }

    // Get registration by ID
    public function getById($id) {
        try {
            $sql = "SELECT * FROM registrations WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Get Registration Error: " . $e->getMessage());
            return null;
        }
    }
}
