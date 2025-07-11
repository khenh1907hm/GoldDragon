<?php
require_once __DIR__ . '/../includes/Database.php';

class Student {
    private $db;

    public function __construct() {
        try {
            $this->db = Database::getInstance()->getConnection();
        } catch (Exception $e) {
            error_log("Student Model Error: " . $e->getMessage());
            throw new Exception("Failed to initialize model");
        }
    }

    // Get all students with pagination and search
    public function getAll($page = 1, $search = '') {
        $limit = 10; // Records per page
        $offset = ($page - 1) * $limit;

        try {
            // Base SQL parts
            $baseSql = "FROM students";
            $whereClause = "";
            $params = [];

            // Build search query
            if (!empty($search)) {
                $searchTerm = '%' . $search . '%';
                $whereClause = " WHERE LOWER(full_name) LIKE LOWER(:search) 
                               OR LOWER(nick_name) LIKE LOWER(:search) 
                               OR LOWER(parent_name) LIKE LOWER(:search) 
                               OR LOWER(phone) LIKE LOWER(:search)";
                $params[':search'] = $searchTerm;
            }

            // DEBUG LOG
            error_log('DEBUG SQL: SELECT * ' . $baseSql . $whereClause . ' ORDER BY created_at DESC LIMIT :limit OFFSET :offset');
            error_log('DEBUG PARAMS: ' . print_r(['search' => $search ?? '', 'limit' => $limit, 'offset' => $offset], true));

            // Get total records for pagination
            $countSql = "SELECT COUNT(*) " . $baseSql . $whereClause;
            $countStmt = $this->db->prepare($countSql);
            foreach ($params as $key => $value) {
                $countStmt->bindValue($key, $value, PDO::PARAM_STR);
            }
            $countStmt->execute();
            $totalRecords = (int) $countStmt->fetchColumn();
            $totalPages = ceil($totalRecords / $limit);

            // Get filtered and paginated records
            $sql = "SELECT * " . $baseSql . $whereClause . " ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
            $stmt = $this->db->prepare($sql);

            // Bind search params
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value, PDO::PARAM_STR);
            }
            // Bind limit/offset as integer
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // DEBUG LOG
            error_log('DEBUG RESULT COUNT: ' . count($students));
            error_log('DEBUG RESULT SAMPLE: ' . print_r(array_slice($students, 0, 2), true));

            return [
                'students' => $students,
                'totalPages' => $totalPages,
                'currentPage' => $page
            ];

        } catch (Exception $e) {
            error_log("Get All Students Error: " . $e->getMessage());
            return [
                'students' => [],
                'totalPages' => 0,
                'currentPage' => 1
            ];
        }
    }

    // Get student by ID
    public function getById($id) {
        try {
            $sql = "SELECT * FROM students WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Get Student Error: " . $e->getMessage());
            return false;
        }
    }

    // Create new student
    public function create($data) {
        try {
            $sql = "INSERT INTO students (full_name, nick_name, age, parent_name, phone, address, notes, created_at) 
                    VALUES (:full_name, :nick_name, :age, :parent_name, :phone, :address, :notes, NOW())";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':full_name', $data['full_name']);
            $stmt->bindParam(':nick_name', $data['nick_name']);
            $stmt->bindParam(':age', $data['age']);
            $stmt->bindParam(':parent_name', $data['parent_name']);
            $stmt->bindParam(':phone', $data['phone']);
            $stmt->bindParam(':address', $data['address']);
            $stmt->bindParam(':notes', $data['notes']);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Create Student Error: " . $e->getMessage());
            return false;
        }
    }

    // Update student
    public function update($id, $data) {
        try {
            $sql = "UPDATE students 
                    SET full_name = :full_name, 
                        nick_name = :nick_name, 
                        age = :age, 
                        parent_name = :parent_name, 
                        phone = :phone, 
                        address = :address, 
                        notes = :notes 
                    WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':full_name', $data['full_name']);
            $stmt->bindParam(':nick_name', $data['nick_name']);
            $stmt->bindParam(':age', $data['age']);
            $stmt->bindParam(':parent_name', $data['parent_name']);
            $stmt->bindParam(':phone', $data['phone']);
            $stmt->bindParam(':address', $data['address']);
            $stmt->bindParam(':notes', $data['notes']);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Update Student Error: " . $e->getMessage());
            return false;
        }
    }

    // Delete student
    public function delete($id) {
        try {
            $sql = "DELETE FROM students WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Delete Student Error: " . $e->getMessage());
            return false;
        }
    }

    // Count total students
    public function count($search = '') {
        try {
            $sql = "SELECT COUNT(*) as total FROM students";
            $params = [];
            
            if (!empty($search)) {
                $sql .= " WHERE full_name LIKE :search OR nick_name LIKE :search OR phone LIKE :search";
                $params[':search'] = "%$search%";
            }
            
            $stmt = $this->db->prepare($sql);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['total'];
        } catch (Exception $e) {
            error_log("Count Students Error: " . $e->getMessage());
            return 0;
        }
    }

    // Get paginated results with search
    public function getPaginated($limit, $offset, $search = '') {
        try {
            $sql = "SELECT * FROM students";
            $params = [];
            
            if (!empty($search)) {
                $sql .= " WHERE full_name LIKE :search OR nick_name LIKE :search OR phone LIKE :search";
                $params[':search'] = "%$search%";
            }
            
            $sql .= " ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
            $params[':limit'] = $limit;
            $params[':offset'] = $offset;
            
            $stmt = $this->db->prepare($sql);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
            }
            $stmt->execute();
            return $stmt;
        } catch (Exception $e) {
            error_log("Get Paginated Students Error: " . $e->getMessage());
            return false;
        }
    }

    // Search students by keyword
    public function searchStudents($keyword) {
        try {
            if (!$this->db) {
                error_log("Database connection is null in searchStudents");
                return false;
            }

            // Test database connection
            try {
                $testStmt = $this->db->query("SELECT 1");
                $testStmt->fetch();
            } catch (PDOException $e) {
                error_log("Database connection test failed in searchStudents: " . $e->getMessage());
                return false;
            }

            $keyword = '%' . $keyword . '%';
            $sql = "SELECT * FROM students 
                   WHERE full_name LIKE :keyword 
                   OR nick_name LIKE :keyword 
                   OR phone LIKE :keyword 
                   OR parent_name LIKE :keyword
                   ORDER BY full_name ASC";
            
            error_log("Search SQL: " . $sql);
            error_log("Search keyword: " . $keyword);
            
            $stmt = $this->db->prepare($sql);
            if (!$stmt) {
                error_log("Failed to prepare statement: " . print_r($this->db->errorInfo(), true));
                return false;
            }

            $stmt->bindValue(':keyword', $keyword, PDO::PARAM_STR);
            
            try {
                $success = $stmt->execute();
                if (!$success) {
                    error_log("Failed to execute statement: " . print_r($stmt->errorInfo(), true));
                    return false;
                }
            } catch (PDOException $e) {
                error_log("Execute error: " . $e->getMessage());
                return false;
            }
            
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("Number of results found: " . count($results));
            
            return $results;
        } catch (PDOException $e) {
            error_log("Error in searchStudents: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            return false;
        }
    }
}
?>