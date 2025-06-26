<?php
// $categories, $photos đã được truyền vào từ controller
// Tạo map id => name cho categories
$categoryMap = [];
foreach ($categories as $cat) {
    $categoryMap[$cat['id']] = $cat['name'];
}
// Phân trang
$perPage = 32;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$totalPhotos = count($photos);
$pageCount = max(1, ceil($totalPhotos / $perPage));
if ($page > $pageCount) $page = $pageCount;
$start = ($page - 1) * $perPage;
$photosPage = array_slice($photos, $start, $perPage);
?>
<style>
.table thead th {
    background: #1976d2;
    color: #fff;
}
.table-bordered {
    border: 1.5px solid #1976d2;
}
.table-bordered td, .table-bordered th {
    border: 1px solid #1976d2;
}
.btn-add {
    background: #2196f3;
    color: #fff;
    border: none;
}
.btn-add:hover {
    background: #1565c0;
    color: #fff;
}
.btn-cate {
    background: #43a047;
    color: #fff;
    border: none;
}
.btn-cate:hover {
    background: #2e7031;
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
td, th {
    color: #222;
    font-size: 15px;
}
</style>
<div class="container p-6">
    <h1 class="text-2xl font-bold mb-4">Danh sách hình ảnh nhóm trẻ</h1>
    <div class="mb-4 flex flex-wrap gap-2 items-center">
        <form method="get" action="" class="inline-block">
            <input type="hidden" name="page" value="photos">
            <select name="category_id" onchange="this.form.submit()" class="border rounded px-3 py-2">
                <option value="0">Tất cả danh mục</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= (isset($_GET['category_id']) && $_GET['category_id'] == $cat['id']) ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </form>
        <a href="index.php?page=photos-create" class="btn btn-add px-4 py-2 rounded ml-2"><i class="fas fa-plus me-1"></i> Đăng ảnh mới</a>
        <a href="index.php?page=photos-categories" class="btn btn-cate px-4 py-2 rounded ml-2"><i class="fas fa-folder-plus me-1"></i> Thêm danh mục</a>
    </div>
    <?php if (empty($photos)): ?>
        <div class="text-gray-500">Chưa có hình ảnh nào.</div>
    <?php else: ?>
    <div class="overflow-x-auto">
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-light">
            <tr>
                <th scope="col">STT</th>
                <th scope="col">Ảnh</th>
                <th scope="col">Danh mục</th>
                <th scope="col">Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($photosPage as $i => $photo): ?>
            <tr>
                <td><?= $start + $i + 1 ?></td>
                <td>
                    <img src="/RongVang/<?= htmlspecialchars($photo['image_path']) ?>" alt="Ảnh nhóm" style="max-width:100px; max-height:80px; border-radius:6px; border:1px solid #ddd;">
                </td>
                <td><?= htmlspecialchars($categoryMap[$photo['category_id']] ?? 'Không rõ') ?></td>
                <td>
                    <form method="post" action="index.php?page=photos-delete" onsubmit="return confirm('Bạn có chắc muốn xóa ảnh này?');" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $photo['id'] ?>">
                        <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Xóa</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    <!-- Phân trang -->
    <?php if ($pageCount > 1): ?>
    <div class="flex justify-center mt-10">
        <nav class="inline-flex gap-1" aria-label="Pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?= $page-1 ?>" class="px-4 py-2 rounded-lg bg-blue-100 text-blue-800 hover:bg-blue-200 transition">&laquo; Prev</a>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $pageCount; $i++): ?>
                <?php if ($i == $page): ?>
                    <span class="px-4 py-2 rounded-lg bg-blue-500 text-white font-bold cursor-default"><?= $i ?></span>
                <?php else: ?>
                    <a href="?page=<?= $i ?>" class="px-4 py-2 rounded-lg bg-blue-100 text-blue-800 hover:bg-blue-200 transition"><?= $i ?></a>
                <?php endif; ?>
            <?php endfor; ?>
            <?php if ($page < $pageCount): ?>
                <a href="?page=<?= $page+1 ?>" class="px-4 py-2 rounded-lg bg-blue-100 text-blue-800 hover:bg-blue-200 transition">Next &raquo;</a>
            <?php endif; ?>
        </nav>
    </div>
    <?php endif; ?>
    <?php endif; ?>
</div> 