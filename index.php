<?php
// Base path configuration
define('BASE_PATH', __DIR__);

// Router đơn giản
$request = $_SERVER['REQUEST_URI'];
// Remove query string and trailing slash
$request = strtok($request, '?');
$request = rtrim($request, '/');

$baseUrl = '/RongVang';

// Extract the base part of the URL if the site is in a subdirectory
$basePath = '/RongVang';
$request = str_replace($basePath, '', $request);
if (empty($request)) {
    $request = '/';
}

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
