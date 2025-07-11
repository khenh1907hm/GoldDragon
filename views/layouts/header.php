<?php
session_start();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' : ''; ?>Golden Dragon Kindergarten</title>
    <link rel="shortcut icon" href="/RongVang/asset/img/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="/RongVang/asset/css/home.css">
    <link rel="stylesheet" href="/RongVang/asset/css/responsive.css">
    <link rel="stylesheet" href="/RongVang/asset/css/montessori.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <?php 
    if (!isset($pageTitle) || $pageTitle === 'Trang chủ'): 
    ?>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <?php endif; ?>

    <header>
        <div class="logo">
            <img src="/RongVang/asset/img/logo.png" alt="Golden Dragon Kindergarten">
        </div>
        <nav>
            <a href="/RongVang/">Trang chủ</a>
            <!-- <a href="/RongVang/tin-tuc">Tin tức</a> -->
            <a href="/RongVang/lich-hoc">Lịch học</a>
            <a href="/RongVang/thuc-don">Thực đơn</a>
            <a href="/RongVang/ban-tin">Bản tin</a>
            <a href="/RongVang/anh-nhom-tre">Ảnh nhóm trẻ</a>
            <a href="/RongVang/tuyen-sinh">Tuyển sinh</a>
            <a href="/RongVang/lien-he">Liên hệ</a>
        </nav>
        <!-- Mobile Menu Button -->
        <button class="mobile-menu-btn" onclick="toggleMobileMenu()">☰</button>
    </header>

    <!-- Mobile Menu -->
    <div class="mobile-menu">
        <a href="/RongVang/">Trang chủ</a>
        <!-- <a href="/RongVang/tin-tuc">Tin tức</a> -->
        <a href="/RongVang/lich-hoc">Lịch học</a>
        <a href="/RongVang/thuc-don">Thực đơn</a>
        <a href="/RongVang/ban-tin">Bản tin</a>
        <a href="/RongVang/anh-nhom-tre">Ảnh nhóm trẻ</a>
        <a href="/RongVang/tuyen-sinh">Tuyển sinh</a>
        <a href="/RongVang/lien-he">Liên hệ</a>
    </div>

    <!-- JavaScript -->
