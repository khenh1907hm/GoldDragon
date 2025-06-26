<?php
// $categories, $message đã được truyền vào từ controller
?>
<style>
.form-upload {
    max-width: 420px;
    margin: 48px auto 0 auto;
    background: #f8fafc;
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(25, 118, 210, 0.08);
    padding: 32px 28px 24px 28px;
}
.btn-upload {
    background: #2196f3;
    color: #fff;
    border: none;
}
.btn-upload:hover {
    background: #1565c0;
    color: #fff;
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
input[type="file"], select, input[type="text"] {
    border: 1.5px solid #1976d2;
    color: #222;
    font-size: 15px;
    background: #fff;
}
label, h1, .font-semibold, span {
    color: #222;
}
.alert-success, .bg-green-100 {
    background: #e8f5e9 !important;
    color: #388e3c !important;
    border: 1px solid #43a047;
}
</style>
<div class="form-upload">
    <h1 class="text-2xl font-bold mb-4">Đăng ảnh nhóm trẻ</h1>
    <?php if (!empty($message)): ?>
        <div class="mb-4 p-3 alert-success rounded"> <?= htmlspecialchars($message) ?> </div>
    <?php endif; ?>
    <form method="post" enctype="multipart/form-data" class="space-y-4">
        <div>
            <label class="block mb-1 font-semibold">Danh mục</label>
            <select name="category_id" required class="w-full rounded px-3 py-2">
                <option value="">-- Chọn danh mục --</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>"> <?= htmlspecialchars($cat['name']) ?> </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="block mb-1 font-semibold">Chọn ảnh</label>
            <input type="file" name="image" accept="image/*" required class="w-full rounded px-3 py-2">
        </div>
        <div class="flex gap-2 mt-4">
            <button type="submit" class="btn btn-upload px-4 py-2 rounded">Tải lên</button>
            <a href="index.php?page=photos" class="btn btn-back px-4 py-2 rounded">Quay lại</a>
        </div>
    </form>
</div> 