<?php
require_once __DIR__ . '/../includes/Pagination.php';

class Registration {
    private $conn;
    private $table = 'registrations';
    private $perPage = 15; // Số đăng ký trên mỗi trang

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create Registration
    public function create($data) {
        $query = "INSERT INTO " . $this->table . " 
                (student_name, nick_name, age, parent_name, phone, address, content) 
                VALUES 
                (:student_name, :nick_name, :age, :parent_name, :phone, :address, :content)";
        
        $stmt = $this->conn->prepare($query);
        
        // Clean data
        $student_name = htmlspecialchars(strip_tags($data['student_name']));
        $nick_name = htmlspecialchars(strip_tags($data['nick_name']));
        $age = htmlspecialchars(strip_tags($data['age']));
        $parent_name = htmlspecialchars(strip_tags($data['parent_name']));
        $phone = htmlspecialchars(strip_tags($data['phone']));
        $address = htmlspecialchars(strip_tags($data['address']));
        $content = htmlspecialchars(strip_tags($data['content']));

        // Bind data
        $stmt->bindParam(':student_name', $student_name);
        $stmt->bindParam(':nick_name', $nick_name);
        $stmt->bindParam(':age', $age);
        $stmt->bindParam(':parent_name', $parent_name);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':content', $content);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Read all registrations with pagination and filters
    public function read($page = 1, $search = '', $status = '') {
        $offset = ($page - 1) * $this->perPage;
        
        $query = "SELECT * FROM " . $this->table . " WHERE 1=1";
        $params = [];
        
        // Add search condition
        if (!empty($search)) {
            $query .= " AND (student_name LIKE :search OR parent_name LIKE :search OR phone LIKE :search)";
            $params[':search'] = "%$search%";
        }
        
        // Add status filter
        if (!empty($status) && $status !== 'all') {
            $query .= " AND status = :status";
            $params[':status'] = $status;
        }
        
        $query .= " ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
        $params[':limit'] = $this->perPage;
        $params[':offset'] = $offset;
        
        $stmt = $this->conn->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $stmt->execute();
        return $stmt;
    }

    // Get pagination object
    public function getPagination($page = 1, $search = '', $status = '') {
        $totalItems = $this->count($search, $status);
        return Pagination::create($totalItems, $this->perPage, $page);
    }

    // Update registration status
    public function updateStatus($id, $status) {
        $query = "UPDATE " . $this->table . " SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        
        // Clean data
        $status = htmlspecialchars(strip_tags($status));
        
        // Bind data
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':status', $status);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Delete registration
    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Get total count of registrations with filters
    public function count($search = '', $status = '') {
        $query = "SELECT COUNT(*) as total FROM " . $this->table . " WHERE 1=1";
        $params = [];
        
        // Add search condition
        if (!empty($search)) {
            $query .= " AND (student_name LIKE :search OR parent_name LIKE :search OR phone LIKE :search)";
            $params[':search'] = "%$search%";
        }
        
        // Add status filter
        if (!empty($status) && $status !== 'all') {
            $query .= " AND status = :status";
            $params[':status'] = $status;
        }
        
        $stmt = $this->conn->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    // Get recent registrations
    public function getRecent($limit = 5) {
        $query = "SELECT * FROM " . $this->table . " ORDER BY created_at DESC LIMIT :limit";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get registration by ID
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get statistics
    public function getStats() {
        $stats = [];
        
        // Total registrations
        $query = "SELECT COUNT(*) as total FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['total'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Pending registrations
        $query = "SELECT COUNT(*) as pending FROM " . $this->table . " WHERE status = 'pending'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['pending'] = $stmt->fetch(PDO::FETCH_ASSOC)['pending'];
        
        // Approved registrations
        $query = "SELECT COUNT(*) as approved FROM " . $this->table . " WHERE status = 'approved'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['approved'] = $stmt->fetch(PDO::FETCH_ASSOC)['approved'];
        
        // Today's registrations
        $query = "SELECT COUNT(*) as today FROM " . $this->table . " WHERE DATE(created_at) = CURDATE()";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['today'] = $stmt->fetch(PDO::FETCH_ASSOC)['today'];
        
        return $stats;
    }
}
