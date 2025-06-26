<?php
require_once __DIR__ . '/../includes/Database.php';
require_once __DIR__ . '/../models/Photo.php';
require_once __DIR__ . '/../models/PhotoCategory.php';

class PhotoController {
    private $db;
    private $photoModel;
    private $categoryModel;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->photoModel = new Photo($this->db);
        $this->categoryModel = new PhotoCategory($this->db);
    }

    // Hiển thị danh sách ảnh, filter theo danh mục
    public function index() {
        $category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;
        $categories = $this->categoryModel->getAll();
        if ($category_id) {
            $photos = $this->photoModel->getByCategory($category_id);
        } else {
            $photos = $this->photoModel->getAll();
        }
        require __DIR__ . '/../admin/views/photos/index.php';
    }

    // Hiển thị form và xử lý upload ảnh mới
    public function create() {
        $categories = $this->categoryModel->getAll();
        $message = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $category_id = (int)($_POST['category_id'] ?? 0);
            if ($category_id && isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../uploads/photos/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $filename = uniqid('photo_', true) . '.' . $ext;
                $targetPath = $uploadDir . $filename;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                    $image_path = 'uploads/photos/' . $filename;
                    $this->photoModel->create($category_id, $image_path);
                    $message = 'Tải ảnh thành công!';
                } else {
                    $message = 'Lỗi khi tải ảnh lên.';
                }
            } else {
                $message = 'Vui lòng chọn danh mục và ảnh.';
            }
        }
        require __DIR__ . '/../admin/views/photos/create.php';
    }

    // Xóa ảnh
    public function delete() {
        if (isset($_POST['id'])) {
            $id = (int)$_POST['id'];
            // Lấy đường dẫn ảnh để xóa file vật lý
            $photo = null;
            $photos = $this->photoModel->getAll();
            foreach ($photos as $p) {
                if ($p['id'] == $id) {
                    $photo = $p;
                    break;
                }
            }
            if ($photo) {
                $filePath = __DIR__ . '/../' . $photo['image_path'];
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
                $this->photoModel->delete($id);
            }
        }
        header('Location: index.php?page=photos');
        exit();
    }

    // Quản lý danh mục: hiển thị, thêm, xóa
    public function categories() {
        $message = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['add_category'])) {
                $name = trim($_POST['name'] ?? '');
                if ($name) {
                    $this->categoryModel->create($name);
                    $message = 'Thêm danh mục thành công!';
                } else {
                    $message = 'Tên danh mục không được để trống.';
                }
            } elseif (isset($_POST['delete_category'])) {
                $id = (int)$_POST['id'];
                $this->categoryModel->delete($id);
                $message = 'Đã xóa danh mục.';
            }
        }
        $categories = $this->categoryModel->getAll();
        require __DIR__ . '/../admin/views/photos/categories.php';
    }
} 