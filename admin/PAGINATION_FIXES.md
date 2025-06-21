# Sửa lỗi Phân trang - Registration Page

## Lỗi gốc
```
Fatal error: Uncaught Error: Call to undefined method RegistrationController::read() 
in C:\xampp\htdocs\RongVang\admin\views\registrations.php:11
```

## Nguyên nhân
1. Trong file `admin/views/registrations.php` đang gọi `$registrationController->read()` 
2. Nhưng method `read()` không tồn tại trong `RegistrationController`
3. `RegistrationController` có method `getAll()` thay vì `read()`

## Các thay đổi đã thực hiện

### 1. Sửa file `admin/views/registrations.php`
- **Trước**: Sử dụng `$registrationController->read()`
- **Sau**: Sử dụng `$registrationModel->read()` với model Registration

```php
// Thêm includes
require_once __DIR__ . '/../../includes/Pagination.php';
require_once __DIR__ . '/../../models/Registration.php';
require_once __DIR__ . '/../../includes/Database.php';

// Khởi tạo model
$db = Database::getInstance()->getConnection();
$registrationModel = new Registration($db);

// Sử dụng model thay vì controller
$result = $registrationModel->read($currentPage, $search, $status);
$pagination = $registrationModel->getPagination($currentPage, $search, $status);
$stats = $registrationModel->getStats();
```

### 2. Sửa file `admin/index.php`
- **Trước**: Gọi `$registrationController->updateStatus($registrationId, $status)`
- **Sau**: Sử dụng model Registration cho update status

```php
// Handle registration operations
if ($action === 'registrations' && $operation === 'update-status' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $registrationId = $_POST['id'] ?? null;
    $status = $_POST['status'] ?? 'approved';
    
    if ($registrationId) {
        // Sử dụng model Registration thay vì controller
        require_once __DIR__ . '/../models/Registration.php';
        $db = Database::getInstance()->getConnection();
        $registrationModel = new Registration($db);
        
        $result = $registrationModel->updateStatus($registrationId, $status);
        if ($result) {
            $_SESSION['success'] = "Registration status updated successfully!";
        } else {
            $_SESSION['error'] = "Failed to update registration status. Please try again.";
        }
    }
    header('Location: index.php?page=registrations');
    exit();
}
```

## Lý do sử dụng Model thay vì Controller

1. **Separation of Concerns**: Model xử lý logic database, Controller xử lý HTTP requests
2. **Reusability**: Model có thể được sử dụng ở nhiều nơi
3. **Consistency**: Các trang khác (posts, students) đã sử dụng model
4. **Maintainability**: Dễ bảo trì và mở rộng

## Kiểm tra sau khi sửa

### 1. Syntax check
```bash
php -l admin/views/registrations.php
php -l admin/index.php
```

### 2. Test file
Tạo file `admin/test_pagination.php` để test hệ thống phân trang

### 3. Chức năng cần test
- [ ] Trang registrations load được
- [ ] Phân trang hoạt động
- [ ] Tìm kiếm hoạt động
- [ ] Lọc theo status hoạt động
- [ ] Update status hoạt động
- [ ] Statistics hiển thị đúng

## Cấu trúc Model Registration

Model `Registration` có các method chính:
- `read($page, $search, $status)` - Lấy dữ liệu với phân trang
- `getPagination($page, $search, $status)` - Tạo object phân trang
- `count($search, $status)` - Đếm tổng số records
- `getStats()` - Lấy thống kê
- `updateStatus($id, $status)` - Cập nhật trạng thái

## Lưu ý

1. **Database Connection**: Mỗi model cần một database connection riêng
2. **Error Handling**: Tất cả database operations đều có try-catch
3. **Security**: Sử dụng prepared statements để tránh SQL injection
4. **Performance**: Sử dụng LIMIT và OFFSET cho phân trang

## Troubleshooting

Nếu vẫn gặp lỗi:

1. **Kiểm tra database connection**:
```php
$db = Database::getInstance()->getConnection();
if (!$db) {
    die("Database connection failed");
}
```

2. **Kiểm tra table registrations**:
```sql
DESCRIBE registrations;
SELECT COUNT(*) FROM registrations;
```

3. **Kiểm tra file permissions**:
- Đảm bảo PHP có quyền đọc các file includes
- Đảm bảo database credentials đúng

4. **Enable error reporting**:
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

## Kết quả

Sau khi sửa, trang registrations sẽ:
- ✅ Load được không lỗi
- ✅ Hiển thị phân trang
- ✅ Hỗ trợ tìm kiếm và lọc
- ✅ Có thể update status
- ✅ Hiển thị statistics
- ✅ Responsive design 