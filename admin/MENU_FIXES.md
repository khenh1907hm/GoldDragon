# Sửa lỗi hiển thị dữ liệu menu trong admin panel

## Vấn đề
Trang admin menu không hiển thị được dữ liệu các ngày trong tuần.

## Nguyên nhân
1. **Xung đột tên biến**: Trong file `admin/views/menus.php`, biến `$menu` được sử dụng vừa là instance của class Menu, vừa là biến trong vòng lặp foreach, gây ra xung đột.
2. **Logic format dữ liệu**: Code không kiểm tra xem dữ liệu đã được format từ model chưa.

## Giải pháp đã áp dụng

### 1. Sửa xung đột tên biến
- Đổi tên biến `$menu` thành `$menuModel` để tránh xung đột
- Đổi tên biến trong vòng lặp từ `$menu` thành `$menuItem`

### 2. Cải thiện logic format dữ liệu
- Kiểm tra xem menu đã được format từ model chưa bằng cách kiểm tra `isset($menuItem['monday']) && is_array($menuItem['monday'])`
- Nếu đã format thì sử dụng trực tiếp, nếu chưa thì format lại

### 3. Thêm require model
- Đảm bảo model Menu được include đúng cách

## Kết quả
- Dữ liệu menu hiện đã hiển thị đúng cách trong admin panel
- Bảng hiển thị đầy đủ thông tin các ngày trong tuần (Thứ 2 đến Thứ 6)
- Mỗi ngày có 3 bữa: Sáng, Trưa, Xế

## Dữ liệu mẫu hiện có
- Tuần 25 (16/06/2025 - 20/06/2025)
- Thứ 2: Cơm, phở, xế
- Thứ 3: phở, phở, phở  
- Thứ 4: phở, phở, cơm
- Thứ 5: nui, nui, nui
- Thứ 6: bún, bún, bún

## Cách kiểm tra
1. Truy cập admin panel
2. Vào trang "Quản lý thực đơn"
3. Kiểm tra bảng hiển thị dữ liệu menu
4. Thêm `?debug=1` vào URL để xem dữ liệu debug

## Files đã sửa
- `admin/views/menus.php`: Sửa logic hiển thị menu 