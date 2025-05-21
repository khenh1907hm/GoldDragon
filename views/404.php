<?php
$pageTitle = 'Không tìm thấy trang';
require __DIR__ . '/../views/layouts/header.php';
?>

<div class="container">
    <div class="row justify-content-center my-5">
        <div class="col-md-8 text-center">
            <h1 class="display-1">404</h1>
            <h2 class="mb-4">Không tìm thấy trang</h2>
            <p class="lead mb-4">Xin lỗi, trang bạn đang tìm kiếm không tồn tại hoặc đã được di chuyển.</p>
            <a href="/" class="btn btn-primary">Quay về trang chủ</a>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../views/layouts/footer.php'; ?>
