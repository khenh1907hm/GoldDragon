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
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405); // Method Not Allowed
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            exit();
        }

        try {
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Invalid JSON data received');
            }
            
            // Sanitize and prepare data
            $registrationData = [
                'student_name' => trim(filter_var($data['student_name'] ?? '', FILTER_SANITIZE_STRING)),
                'nick_name'    => trim(filter_var($data['nick_name'] ?? '', FILTER_SANITIZE_STRING)),
                'age'          => filter_var($data['age'] ?? 0, FILTER_VALIDATE_INT, ['options' => ['min_range' => 2, 'max_range' => 6]]),
                'parent_name'  => trim(filter_var($data['parent_name'] ?? '', FILTER_SANITIZE_STRING)),
                'phone'        => trim(filter_var($data['phone'] ?? '', FILTER_SANITIZE_STRING)),
                'address'      => trim(filter_var($data['address'] ?? '', FILTER_SANITIZE_STRING)),
                'content'      => trim(filter_var($data['content'] ?? '', FILTER_SANITIZE_STRING))
            ];

            // Validate required fields after sanitization
            if (empty($registrationData['student_name']) || empty($registrationData['parent_name']) || empty($registrationData['phone']) || empty($registrationData['address']) || empty($registrationData['content'])) {
                throw new Exception("Vui lòng điền đầy đủ các trường bắt buộc.");
            }
            if ($registrationData['age'] === false) {
                throw new Exception("Tuổi của bé không hợp lệ (phải từ 2 đến 6).");
            }
            
            // Validate phone number format (simple Vietnamese format check)
            if (!preg_match('/^(0[3|5|7|8|9])[0-9]{8}$/', $registrationData['phone'])) {
                throw new Exception("Số điện thoại không hợp lệ.");
            }

            // Begin transaction
            $this->db->beginTransaction();

            try {
                $sql = "INSERT INTO registrations (parent_name, student_name, nick_name, age, phone, address, content, status, created_at) 
                        VALUES (:parent_name, :student_name, :nick_name, :age, :phone, :address, :content, 'pending', NOW())";
                
                $stmt = $this->db->prepare($sql);
                $stmt->execute($registrationData);

                $registrationId = $this->db->lastInsertId();
                $this->db->commit();
                
                // Get full new registration data for email
                $newRegistration = $this->getById($registrationId);

                // Try to send email notification
                if ($newRegistration) {
                    try {
                        $this->mailer->sendNewRegistrationNotification($newRegistration);
                    } catch (Exception $e) {
                        // Log email error but don't fail the registration process
                        error_log("Email sending failed for registration ID {$registrationId}: " . $e->getMessage());
                    }
                }
                
                http_response_code(201); // Created
                echo json_encode([
                    'success' => true,
                    'message' => 'Đăng ký thành công! Chúng tôi sẽ liên hệ với bạn sớm nhất.'
                ]);

            } catch (Exception $e) {
                $this->db->rollBack();
                throw $e; // Rethrow to be caught by the outer catch block
            }
        } catch (Exception $e) {
            http_response_code(400); // Bad Request
            error_log("Registration Error: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit();
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
