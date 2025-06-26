<?php
// Base path configuration
define('BASE_PATH', __DIR__);

// Router đơn giản
$request = $_SERVER['REQUEST_URI'];
// Lấy đường dẫn URI hiện tại từ server (ví dụ: /RongVang/tin-tuc?abc=1)

$request = strtok($request, '?');
// strtok($request, '?'): Cắt chuỗi $request tại dấu '?' đầu tiên, trả về phần trước dấu '?'. Tham số 1: chuỗi nguồn, Tham số 2: ký tự phân tách.

$request = rtrim($request, '/');
// rtrim($request, '/'): Loại bỏ dấu '/' ở cuối chuỗi $request nếu có. Tham số 1: chuỗi nguồn, Tham số 2: ký tự muốn loại bỏ ở cuối.

// Tự động lấy base path nếu web nằm trong thư mục con hoặc gốc
$basePath = dirname($_SERVER['SCRIPT_NAME']);
if ($basePath === '/' || $basePath === '\\') $basePath = '';
$baseUrl = $basePath;

$request = str_replace($basePath, '', $request);
// str_replace($basePath, '', $request): Thay thế tất cả $basePath trong $request thành chuỗi rỗng. Tham số 1: chuỗi cần thay, Tham số 2: chuỗi thay thế, Tham số 3: chuỗi nguồn.

if (empty($request)) {
    $request = '/';
}
// Kiểm tra nếu $request rỗng thì gán là '/'

// Xử lý route
switch ($request) {
    case '':
    case '/':
        $pageTitle = 'Trang chủ';
        require BASE_PATH . '/views/home.php';
        break;
    case '/tin-tuc':
        $pageTitle = 'Tin tức';
        require BASE_PATH . '/views/pages/tin-tuc.php';
        break;
    case '/lich-hoc':
        $pageTitle = 'Lịch học';
        require BASE_PATH . '/views/pages/lich-hoc.php';
        break;
    case '/thuc-don':
        $pageTitle = 'Thực đơn';
        require BASE_PATH . '/views/pages/thuc-don.php';
        break;
    case '/ban-tin':
        $pageTitle = 'Bản tin';
        require BASE_PATH . '/views/pages/tin-tuc.php';
        break;
    case '/anh-nhom-tre':
        $pageTitle = 'Ảnh nhóm trẻ';
        require BASE_PATH . '/views/pages/anh-nhom-tre.php';
        break;
    case '/tuyen-sinh':
        $pageTitle = 'Tuyển sinh';
        require BASE_PATH . '/views/pages/tuyen-sinh.php';
        break;
    case '/lien-he':
        $pageTitle = 'Liên hệ';
        require __DIR__ . '/views/pages/lien-he.php';
        break;
    default:
        http_response_code(404);
        require __DIR__ . '/views/404.php';
        break;
}
