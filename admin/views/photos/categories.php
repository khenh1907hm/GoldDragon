<?php
// $categories, $message đã được truyền vào từ controller
?>
<style>
.btn-add {
    background: #2196f3;
    color: #fff;
    border: none;
}
.btn-add:hover {
    background: #1565c0;
    color: #fff;
}
.btn-danger {
    background: #e53935 !important;
    color: #fff !important;
    border: none;
}
.btn-danger:hover {
    background: #b71c1c !important;
    color: #fff !important;
}
.btn-back {
    background: #616161;
    color: #fff;
    border: none;
}
.btn-back:hover {
    background: #222;
    color: #fff;
}
input[type="text"] {
    border: 1.5px solid #1976d2;
    color: #222;
    font-size: 15px;
}
label, h1, h2, .font-semibold, span {
    color: #222;
}
.alert-success, .bg-green-100 {
    background: #e8f5e9 !important;
    color: #388e3c !important;
    border: 1px solid #43a047;
}
</style>
<div class="container p-6 max-w-lg mx-auto">
    <h1 class="text-2xl font-bold mb-4">Quản lý danh mục ảnh</h1>
    <?php if (!empty($message)): ?>
        <div class="mb-4 p-3 alert-success rounded"> <?= htmlspecialchars($message) ?> </div>
    <?php endif; ?>
    <form method="post" class="mb-4 flex gap-2">
        <input type="text" name="name" placeholder="Tên danh mục mới" required class="flex-1 px-3 py-2">
        <button type="submit" name="add_category" class="btn btn-add px-4 py-2 rounded">Thêm</button>
    </form>
    <div class="mb-4">
        <h2 class="font-semibold mb-2">Danh sách danh mục</h2>
        <ul class="space-y-2">
            <?php foreach ($categories as $cat): ?>
            <li class="flex items-center justify-between border-b pb-1">
                <span><?= htmlspecialchars($cat['name']) ?></span>
                <form method="post" style="display:inline;" onsubmit="return confirm('Xóa danh mục này?');">
                    <input type="hidden" name="id" value="<?= $cat['id'] ?>">
                    <button type="submit" name="delete_category" class="btn btn-danger px-3 py-1 rounded">Xóa</button>
                </form>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <a href="index.php?page=photos" class="btn btn-back px-4 py-2 rounded">Quay lại danh sách ảnh</a>
</div> 