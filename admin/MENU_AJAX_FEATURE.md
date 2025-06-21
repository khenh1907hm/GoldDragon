# Chức năng AJAX Load Menu khi chọn tuần

## Mô tả
Khi người dùng chọn tuần trong dropdown, hệ thống sẽ tự động kiểm tra xem tuần đó đã có dữ liệu menu chưa. Nếu có, dữ liệu sẽ được load vào form để chỉnh sửa. Nếu chưa có, form sẽ được clear để tạo mới.

## Cách hoạt động

### 1. Khi chọn tuần trong dropdown
- JavaScript lắng nghe sự kiện `change` trên select box
- Lấy thông tin `start_date` và `end_date` từ option được chọn
- Gửi AJAX request đến `index.php?page=menus&action=check`

### 2. Server xử lý request
- Action `check` trong admin/index.php nhận request
- Sử dụng model Menu để kiểm tra menu theo date range
- Trả về JSON response:
  - `exists: true/false`
  - `menu: data` (nếu có)

### 3. JavaScript xử lý response
- Nếu `exists: true`: Load dữ liệu vào form, hiển thị nút "Cập nhật"
- Nếu `exists: false`: Clear form, hiển thị nút "Thêm mới"

## Files đã sửa

### 1. admin/index.php
Thêm 2 action mới:
- `action=check`: Kiểm tra menu theo date range
- `action=get`: Lấy menu theo ID

```php
case 'check':
    // AJAX action to check if menu exists for a week
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        header('Content-Type: application/json');
        $startDate = $_POST['start_date'] ?? '';
        $endDate = $_POST['end_date'] ?? '';
        
        if (!empty($startDate) && !empty($endDate)) {
            require_once __DIR__ . '/../models/Menu.php';
            $menuModel = new Menu();
            $menu = $menuModel->getByDateRange($startDate, $endDate);
            if ($menu) {
                echo json_encode(['exists' => true, 'menu' => $menu]);
            } else {
                echo json_encode(['exists' => false, 'menu' => null]);
            }
        }
        exit();
    }
    break;
```

### 2. admin/views/menus.php
JavaScript đã có sẵn để xử lý:
- Event listener cho `#week_select`
- AJAX request đến action `check`
- Function `loadMenuData()` để fill form
- Function `clearMenuData()` để clear form

## Cách sử dụng

### 1. Truy cập admin panel
- Vào trang "Quản lý thực đơn"

### 2. Chọn tuần
- Chọn tuần từ dropdown "Chọn tuần"
- Hệ thống sẽ tự động kiểm tra dữ liệu

### 3. Kết quả
- **Nếu có dữ liệu**: Form sẽ được fill với dữ liệu hiện có, nút "Cập nhật thực đơn" xuất hiện
- **Nếu chưa có dữ liệu**: Form trống, nút "Thêm thực đơn" xuất hiện

## Ví dụ dữ liệu hiện có
- Tuần 25 (16/06/2025 - 20/06/2025) đã có dữ liệu:
  - Thứ 2: Cơm, phở, xế
  - Thứ 3: phở, phở, phở
  - Thứ 4: phở, phở, cơm
  - Thứ 5: nui, nui, nui
  - Thứ 6: bún, bún, bún

## Lợi ích
1. **UX tốt hơn**: Người dùng không cần nhớ tuần nào đã có dữ liệu
2. **Tránh duplicate**: Hệ thống tự động phát hiện tuần đã có dữ liệu
3. **Chỉnh sửa nhanh**: Load dữ liệu sẵn để chỉnh sửa
4. **Tạo mới dễ dàng**: Form trống sẵn sàng cho tuần mới

## Testing
Để test chức năng:
1. Chọn tuần 25 (đã có dữ liệu) → Form sẽ load dữ liệu
2. Chọn tuần khác (chưa có dữ liệu) → Form sẽ trống
3. Kiểm tra console browser để xem AJAX response 