<?php
require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../includes/Database.php';

class StudentController {
    private $student;
    private $viewPath;
    private $adminPath;
    private $db;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        try {
            $this->db = Database::getInstance()->getConnection();
        } catch (Exception $e) {
            error_log("StudentController Error: " . $e->getMessage());
            throw new Exception("Failed to initialize controller");
        }
        $this->student = new Student($this->db);
        $this->adminPath = dirname(__DIR__) . '/admin/';
        $this->viewPath = $this->adminPath . 'views/students/';
    }

    public function count() {
        try {
            $sql = "SELECT COUNT(*) as total FROM students";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch (Exception $e) {
            error_log("Count Error: " . $e->getMessage());
            return 0;
        }
    }

    public function index() {
    $limit = 10; // số bản ghi mỗi trang
    $page = isset($_GET['page_num']) ? (int)$_GET['page_num'] : 1;
    $offset = ($page - 1) * $limit;

    // Lấy dữ liệu sinh viên giới hạn theo phân trang
    $result = $this->student->getPaginated($limit, $offset);
    $students = $result->fetchAll(PDO::FETCH_ASSOC);

    // Tổng số bản ghi
    $totalStudents = $this->student->getCount();
    $totalPages = ceil($totalStudents / $limit);

    ob_start();
    require $this->viewPath . 'index.php';
    $content = ob_get_clean();

    $_SESSION['page_title'] = "Students";
    require_once dirname(__DIR__) . '/admin/views/layout.php';
}


    public function create() {
    ob_start();
    require $this->viewPath . 'create.php';
    $content = ob_get_clean();
    $_SESSION['page_title'] = "Add New Student";
    require dirname(__DIR__) . '/admin/views/layout.php';
}


    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'full_name' => $_POST['full_name'] ?? '',
                'nick_name' => $_POST['nick_name'] ?? '',
                'age' => $_POST['age'] ?? '',
                'parent_name' => $_POST['parent_name'] ?? '',
                'phone' => $_POST['phone'] ?? '',
                'address' => $_POST['address'] ?? '',
                'notes' => $_POST['notes'] ?? ''
            ];

            if ($this->student->create($data)) {
                $_SESSION['success'] = "Student created successfully";
                header('Location: index.php?page=students');
                exit;
            } else {
                $_SESSION['error'] = "Failed to create student";
                header('Location: index.php?page=students&action=create');
                exit;
            }
        }
    }    public function edit($id) {
        $student = $this->student->getById($id);
        if ($student) {
            ob_start();
            require $this->viewPath . 'edit.php';
            $content = ob_get_clean();
            $_SESSION['page_title'] = "Edit Student";
            require_once dirname(__DIR__) . '/admin/views/layout.php';
        } else {
            $_SESSION['error'] = "Student not found";
            header('Location: index.php?page=students');
            exit;
        }
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'full_name' => $_POST['full_name'] ?? '',
                'nick_name' => $_POST['nick_name'] ?? '',
                'age' => $_POST['age'] ?? '',
                'parent_name' => $_POST['parent_name'] ?? '',
                'phone' => $_POST['phone'] ?? '',
                'address' => $_POST['address'] ?? '',
                'notes' => $_POST['notes'] ?? ''
            ];

            if ($this->student->update($id, $data)) {
                $_SESSION['success'] = "Student updated successfully";
                header('Location: index.php?page=students');
                exit;
            } else {
                $_SESSION['error'] = "Failed to update student";
                header('Location: index.php?page=students&action=edit&id=' . $id);
                exit;
            }
        }
    }

    public function delete($id) {
        if ($this->student->delete($id)) {
            $_SESSION['success'] = "Student deleted successfully";
        } else {
            $_SESSION['error'] = "Failed to delete student";
        }
        header('Location: index.php?page=students');
        exit;
    }
    function getStudents($pdo, $search = '', $sort = '', $page = 1, $limit = 5)
    {
        $offset = ($page - 1) * $limit;
        $whereClause = '';
        $params = [];

        if (!empty($search)) {
            $whereClause = "WHERE full_name LIKE :search OR nick_name LIKE :search";
            $params[':search'] = '%' . $search . '%';
        }

        $orderBy = '';
        if ($sort === 'asc') {
            $orderBy = "ORDER BY full_name ASC";
        } elseif ($sort === 'desc') {
            $orderBy = "ORDER BY full_name DESC";
        } else {
            $orderBy = "ORDER BY id DESC";
        }

        // Tổng số bản ghi
        $countSql = "SELECT COUNT(*) FROM students $whereClause";
        $countStmt = $pdo->prepare($countSql);
        $countStmt->execute($params);
        $totalRecords = $countStmt->fetchColumn();
        $totalPages = ceil($totalRecords / $limit);

        // Truy vấn dữ liệu
        $sql = "SELECT * FROM students $whereClause $orderBy LIMIT :limit OFFSET :offset";
        $stmt = $pdo->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();

        $students = $stmt->fetchAll();

        return [
            'students' => $students,
            'totalPages' => $totalPages,
            'page' => $page,
        ];
    }

    // Get recent students
    public function getRecent($limit = 5) {
        try {
            $sql = "SELECT * FROM students ORDER BY created_at DESC LIMIT :limit";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Get Recent Error: " . $e->getMessage());
            return [];
        }
    }

}
