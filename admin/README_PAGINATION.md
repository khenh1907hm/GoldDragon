# Hệ thống Phân trang cho Admin Panel

## Tổng quan

Hệ thống phân trang đã được tối ưu hóa để xử lý hiệu quả khi có quá nhiều dữ liệu trong trang admin. Hệ thống bao gồm:

- **Class Pagination**: Xử lý logic phân trang
- **Tìm kiếm và lọc**: Hỗ trợ tìm kiếm real-time và lọc theo trạng thái
- **AJAX Support**: Tùy chọn load trang không reload
- **Responsive Design**: Tương thích với mọi thiết bị
- **Performance**: Tối ưu hiệu suất với lazy loading

## Cấu trúc Files

```
admin/
├── includes/
│   └── Pagination.php          # Class xử lý phân trang
├── assets/
│   ├── css/
│   │   └── pagination.css      # CSS tùy chỉnh cho phân trang
│   └── js/
│       └── pagination.js       # JavaScript cho AJAX và UX
├── views/
│   ├── posts.php              # Trang quản lý bài viết với phân trang
│   ├── registrations.php      # Trang quản lý đăng ký với phân trang
│   └── students.php           # Trang quản lý học sinh với phân trang
└── models/
    ├── Post.php               # Model với hỗ trợ phân trang
    ├── Registration.php       # Model với hỗ trợ phân trang
    └── Student.php            # Model với hỗ trợ phân trang
```

## Cách sử dụng

### 1. Trong Model

```php
class YourModel {
    private $perPage = 10; // Số item trên mỗi trang
    
    // Lấy dữ liệu với phân trang
    public function getAll($page = 1, $search = '', $filter = '') {
        $offset = ($page - 1) * $this->perPage;
        
        $sql = "SELECT * FROM your_table WHERE 1=1";
        $params = [];
        
        // Thêm điều kiện tìm kiếm
        if (!empty($search)) {
            $sql .= " AND (title LIKE :search OR content LIKE :search)";
            $params[':search'] = "%$search%";
        }
        
        // Thêm điều kiện lọc
        if (!empty($filter)) {
            $sql .= " AND status = :filter";
            $params[':filter'] = $filter;
        }
        
        $sql .= " ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
        $params[':limit'] = $this->perPage;
        $params[':offset'] = $offset;
        
        // Thực thi query...
    }
    
    // Lấy object phân trang
    public function getPagination($page = 1, $search = '', $filter = '') {
        $totalItems = $this->count($search, $filter);
        return Pagination::create($totalItems, $this->perPage, $page);
    }
    
    // Đếm tổng số records
    public function count($search = '', $filter = '') {
        // Logic đếm với filters...
    }
}
```

### 2. Trong View

```php
<?php
// Lấy dữ liệu và phân trang
$currentPage = isset($_GET['page_num']) ? (int)$_GET['page_num'] : 1;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$status = isset($_GET['status']) ? trim($_GET['status']) : '';

$result = $model->getAll($currentPage, $search, $status);
$items = $result ? $result->fetchAll(PDO::FETCH_ASSOC) : [];

$pagination = $model->getPagination($currentPage, $search, $status);
?>

<!-- Form tìm kiếm -->
<form method="GET" action="index.php" class="d-flex">
    <input type="hidden" name="page" value="your_page">
    <div class="input-group">
        <input type="text" name="search" class="form-control" 
               placeholder="Tìm kiếm..." value="<?php echo htmlspecialchars($search); ?>">
        <button class="btn btn-outline-secondary" type="submit">
            <i class="fas fa-search"></i>
        </button>
    </div>
</form>

<!-- Bảng dữ liệu -->
<table class="table">
    <tbody>
        <?php foreach ($items as $item): ?>
            <!-- Hiển thị dữ liệu -->
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Phân trang -->
<?php if ($pagination->getTotalPages() > 1): ?>
    <div class="mt-4">
        <?php echo $pagination->render([
            'showInfo' => true,
            'showFirstLast' => true,
            'showPrevNext' => true,
            'maxVisible' => 5,
            'alignment' => 'center'
        ]); ?>
    </div>
<?php endif; ?>
```

### 3. Tùy chỉnh Pagination

```php
// Tùy chỉnh hiển thị phân trang
$pagination->render([
    'showInfo' => true,           // Hiển thị thông tin "Showing X to Y of Z"
    'showFirstLast' => true,      // Hiển thị nút First/Last
    'showPrevNext' => true,       // Hiển thị nút Previous/Next
    'maxVisible' => 5,            // Số trang tối đa hiển thị
    'alignment' => 'center',      // Căn chỉnh: left, center, right
    'size' => '',                 // Kích thước: sm, lg
    'class' => 'pagination'       // CSS class
]);
```

## Tính năng nâng cao

### 1. AJAX Pagination

Hệ thống hỗ trợ AJAX để load trang không reload:

```javascript
// Khởi tạo AJAX pagination
new PaginationHandler({
    container: '.pagination-container',
    tableBody: 'tbody',
    searchForm: 'form[method="GET"]',
    filterForm: 'form[method="GET"]'
});
```

### 2. Keyboard Navigation

- `←` (Arrow Left): Trang trước
- `→` (Arrow Right): Trang sau

### 3. Debounced Search

Tìm kiếm tự động sau 500ms khi người dùng ngừng gõ.

### 4. URL Management

URL được cập nhật tự động với các tham số tìm kiếm và lọc.

## Tối ưu hiệu suất

### 1. Database Indexing

Đảm bảo có index cho các cột thường xuyên tìm kiếm:

```sql
-- Index cho tìm kiếm
CREATE INDEX idx_title_content ON posts(title, content);
CREATE INDEX idx_status ON posts(status);
CREATE INDEX idx_created_at ON posts(created_at);

-- Index cho registrations
CREATE INDEX idx_student_name ON registrations(student_name);
CREATE INDEX idx_phone ON registrations(phone);
CREATE INDEX idx_status ON registrations(status);
```

### 2. Query Optimization

- Sử dụng LIMIT và OFFSET cho phân trang
- Tránh SELECT * khi không cần thiết
- Sử dụng prepared statements để tránh SQL injection

### 3. Caching

Có thể thêm caching cho kết quả tìm kiếm:

```php
// Cache key dựa trên parameters
$cacheKey = "search_" . md5($search . $status . $page);

// Kiểm tra cache
if ($cached = cache()->get($cacheKey)) {
    return $cached;
}

// Thực hiện query và cache kết quả
$result = $this->performSearch($search, $status, $page);
cache()->set($cacheKey, $result, 300); // Cache 5 phút

return $result;
```

## Troubleshooting

### 1. Phân trang không hiển thị

- Kiểm tra `$pagination->getTotalPages() > 1`
- Đảm bảo đã include file CSS và JS
- Kiểm tra console browser cho lỗi JavaScript

### 2. Tìm kiếm không hoạt động

- Kiểm tra form method và action
- Đảm bảo input có name="search"
- Kiểm tra logic tìm kiếm trong model

### 3. Performance chậm

- Kiểm tra database indexes
- Sử dụng EXPLAIN để phân tích query
- Giảm số lượng items per page
- Thêm caching nếu cần

## Customization

### 1. Thay đổi số items per page

```php
class YourModel {
    private $perPage = 20; // Thay đổi từ 10 thành 20
}
```

### 2. Tùy chỉnh CSS

Chỉnh sửa file `assets/css/pagination.css` để thay đổi giao diện.

### 3. Thêm filters mới

```php
// Trong model
public function getAll($page = 1, $search = '', $status = '', $category = '') {
    // Thêm logic filter cho category
    if (!empty($category)) {
        $sql .= " AND category = :category";
        $params[':category'] = $category;
    }
}

// Trong view
<select name="category" onchange="this.form.submit()">
    <option value="">All Categories</option>
    <option value="news">News</option>
    <option value="events">Events</option>
</select>
```

## Best Practices

1. **Luôn sử dụng prepared statements** để tránh SQL injection
2. **Giới hạn số items per page** để tránh quá tải
3. **Thêm loading states** cho UX tốt hơn
4. **Validate input** trước khi tìm kiếm
5. **Sử dụng indexes** cho các cột tìm kiếm
6. **Cache kết quả** cho các tìm kiếm phổ biến
7. **Responsive design** cho mobile devices
8. **Accessibility** với ARIA labels và keyboard navigation

## Support

Nếu gặp vấn đề, hãy kiểm tra:
1. Console browser cho JavaScript errors
2. PHP error logs
3. Database query performance
4. Network tab cho AJAX requests 