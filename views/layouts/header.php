<?php
session_start();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' : ''; ?>Golden Dragon Kindergarten</title>
    <link rel="shortcut icon" href="/3D_web_test/asset/img/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="/3D_web_test/asset/css/home.css">
    <link rel="stylesheet" href="/3D_web_test/asset/css/responsive.css">
    <link rel="stylesheet" href="/3D_web_test/asset/css/montessori.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>

    <header>
        <div class="logo">
            <img src="/3D_web_test/asset/img/logo.png" alt="Golden Dragon Kindergarten">
        </div>
        <nav>
            <a href="/3D_web_test/">Trang chủ</a>
            <a href="/3D_web_test/tin-tuc">Tin tức</a>
            <a href="/3D_web_test/lich-hoc">Lịch học</a>
            <a href="/3D_web_test/thuc-don">Thực đơn</a>
            <a href="/3D_web_test/ban-tin">Bản tin</a>
            <a href="/3D_web_test/tuyen-sinh">Tuyển sinh</a>
            <a href="/3D_web_test/lien-he">Liên hệ</a>
        </nav>
    </header>

    <!-- Mobile Menu Button -->
    <button class="mobile-menu-btn" onclick="toggleMobileMenu()">☰</button>

    <!-- Mobile Menu -->
    <div class="mobile-menu">
        <a href="/3D_web_test/">Trang chủ</a>
        <a href="/3D_web_test/tin-tuc">Tin tức</a>
        <a href="/3D_web_test/lich-hoc">Lịch học</a>
        <a href="/3D_web_test/thuc-don">Thực đơn</a>
        <a href="/3D_web_test/ban-tin">Bản tin</a>
        <a href="/3D_web_test/tuyen-sinh">Tuyển sinh</a>
        <a href="/3D_web_test/lien-he">Liên hệ</a>
    </div>

    <!-- JavaScript -->
