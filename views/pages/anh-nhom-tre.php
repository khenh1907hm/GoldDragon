<?php
$pageTitle = 'Ảnh nhóm trẻ';
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../../includes/Database.php';
require_once __DIR__ . '/../../models/PhotoCategory.php';
require_once __DIR__ . '/../../models/Photo.php';

$db = Database::getInstance()->getConnection();
$categoryModel = new PhotoCategory($db);
$photoModel = new Photo($db);

$categories = $categoryModel->getAll();
$photos = $photoModel->getAll();

// Phân trang
$perPage = 32;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$totalPhotos = count($photos);
$pageCount = max(1, ceil($totalPhotos / $perPage));
if ($page > $pageCount) $page = $pageCount;
$start = ($page - 1) * $perPage;
$photosPage = array_slice($photos, $start, $perPage);

// Dữ liệu mẫu cho sidebar
$recentPosts = [
    ['id'=>1,'title'=>'Khai giảng năm học mới','image'=>'asset/img/logo.png','created_at'=>'2024-06-01'],
    ['id'=>2,'title'=>'Ngày hội thể thao cho bé','image'=>'asset/img/logo.png','created_at'=>'2024-06-10'],
    ['id'=>3,'title'=>'Workshop kỹ năng sống','image'=>'asset/img/logo.png','created_at'=>'2024-06-15'],
];
?>
<main class="container mx-auto py-8 px-4">
    <div class="text-center mb-12">
        <h1 class="text-4xl md:text-5xl font-semibold mb-4 text-blue-700 bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-blue-800">Ảnh nhóm trẻ</h1>
        <h2 class="text-2xl md:text-3xl text-blue-600 tracking-wide uppercase">Khoảnh khắc của các bé</h2>
        <div class="w-24 h-1 bg-blue-500 mx-auto mt-4 rounded-full"></div>
    </div>
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Main Content -->
        <div class="lg:w-2/3 lg:border-r lg:border-gray-200 lg:pr-8">
            <div class="flex items-center mb-6 gap-4">
                <label for="cate-select" class="font-semibold text-blue-700 text-lg">Chọn nhóm:</label>
                <select id="cate-select" class="select-cate ml-2">
                    <option value="0">Tất cả</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div id="photo-masonry" class="masonry">
                <?php foreach ($photosPage as $photo): ?>
                <div class="masonry-item" data-cate="<?= $photo['category_id'] ?>">
                    <img src="/RongVang/<?= htmlspecialchars($photo['image_path']) ?>" alt="Ảnh nhóm trẻ" class="zoomable-img">
                </div>
                <?php endforeach; ?>
            </div>
            <div id="no-photo-msg" class="text-center text-gray-500 text-lg mt-8" style="display:none;">Chưa có hình ảnh nào cho nhóm này.</div>
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
        </div>
        <!-- Sidebar -->
        <div class="lg:w-1/3 ">
            <!-- Bài viết mới -->
            <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300 mb-8">
                <h3 class="text-xl font-bold text-green-700 mb-4 flex items-center">
                    <i class="fas fa-newspaper mr-2"></i>
                    Bài viết mới
                </h3>
                <div class="space-y-4">
                    <?php foreach ($recentPosts as $recentPost): ?>
                    <a href="/RongVang/ban-tin/<?php echo $recentPost['id']; ?>" 
                       class="block group transform transition-transform duration-300 hover:-translate-y-1">
                        <div class="flex items-start space-x-4">
                            <div class="relative overflow-hidden rounded-lg w-20 h-20">
                                <img src="/RongVang/<?php echo htmlspecialchars($recentPost['image']); ?>" 
                                     alt="<?php echo htmlspecialchars($recentPost['title']); ?>"
                                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-900 group-hover:text-green-600 transition-colors duration-300">
                                    <?php echo htmlspecialchars($recentPost['title']); ?>
                                </h4>
                                <p class="text-xs text-gray-500 mt-1 flex items-center">
                                    <i class="far fa-calendar-alt mr-1"></i>
                                    <?php echo date('d/m/Y', strtotime($recentPost['created_at'])); ?>
                                </p>
                            </div>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <!-- Thông tin liên hệ -->
            <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                <h3 class="text-xl font-bold text-green-700 mb-4 flex items-center">
                    <i class="fas fa-address-card mr-2"></i>
                    Liên hệ
                </h3>
                <div class="space-y-4">
                    <a href="tel:0839395292" class="flex items-center text-gray-600 hover:text-green-600 transition-colors duration-300">
                        <i class="fas fa-phone-alt w-6"></i>
                        <span>0839395292</span>
                    </a>
                    <a href="mailto:info@goldendragon.edu.vn" class="flex items-center text-gray-600 hover:text-green-600 transition-colors duration-300">
                        <i class="fas fa-envelope w-6"></i>
                        <span>info@goldendragon.edu.vn</span>
                    </a>
                    <div class="flex items-start text-gray-600">
                        <i class="fas fa-map-marker-alt w-6 mt-1"></i>
                        <span>15 Đông Đô, Thị trấn Liên Nghĩa, Đức Trọng, Lâm Đồng</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<style>
.masonry {
  column-count: 4;
  column-gap: 1.5rem;
}
@media (max-width: 1200px) {
  .masonry { column-count: 3; }
}
@media (max-width: 900px) {
  .masonry { column-count: 2; }
}
@media (max-width: 600px) {
  .masonry { column-count: 1; }
}
.masonry-item {
  break-inside: avoid;
  margin-bottom: 1.5rem;
  transition: transform 0.2s, box-shadow 0.2s;
  border-radius: 1rem;
  overflow: hidden;
  box-shadow: 0 2px 12px rgba(25, 118, 210, 0.08);
  background: #fff;
}
.masonry-item img {
  width: 100%;
  display: block;
  border-radius: 1rem;
  transition: transform 0.2s;
}
.masonry-item:hover img {
  transform: scale(1.06);
  box-shadow: 0 4px 24px rgba(25, 118, 210, 0.18);
}
.select-cate {
  border: 2px solid #1976d2;
  border-radius: 8px;
  padding: 0.5rem 1.2rem;
  font-size: 1.1rem;
  color: #1976d2;
  font-weight: 600;
  background: #f8fafc;
  outline: none;
  margin-left: 1rem;
  box-shadow: 0 2px 8px rgba(25, 118, 210, 0.04);
  transition: border 0.2s;
}
.select-cate:focus {
  border: 2.5px solid #0d47a1;
}
/* Overlay zoom ảnh */
#img-zoom-overlay {
  display: none;
  position: fixed;
  z-index: 1000;
  left: 0; top: 0; right: 0; bottom: 0;
  background: rgba(0,0,0,0.7);
  align-items: center;
  justify-content: center;
}
#img-zoom-overlay.active {
  display: flex;
}
#img-zoom-overlay img {
  max-width: 90vw;
  max-height: 80vh;
  border-radius: 1.2rem;
  box-shadow: 0 8px 32px rgba(0,0,0,0.25);
  background: #fff;
}
#img-zoom-overlay .close-btn {
  position: absolute;
  top: 32px;
  right: 48px;
  font-size: 2.5rem;
  color: #fff;
  cursor: pointer;
  z-index: 1100;
  transition: color 0.2s;
}
#img-zoom-overlay .close-btn:hover {
  color: #1976d2;
}
</style>
<div id="img-zoom-overlay">
    <span class="close-btn" onclick="closeImgZoom()">&times;</span>
    <img src="" alt="Zoom ảnh nhóm trẻ" id="img-zoom-target">
</div>
<script>
const select = document.getElementById('cate-select');
const items = document.querySelectorAll('.masonry-item');
const noMsg = document.getElementById('no-photo-msg');
select.addEventListener('change', function() {
    let val = this.value;
    let has = false;
    items.forEach(item => {
        if (val === '0' || item.getAttribute('data-cate') === val) {
            item.style.display = '';
            has = true;
        } else {
            item.style.display = 'none';
        }
    });
    noMsg.style.display = has ? 'none' : '';
});
// Zoom ảnh
const zoomImgs = document.querySelectorAll('.zoomable-img');
const overlay = document.getElementById('img-zoom-overlay');
const zoomTarget = document.getElementById('img-zoom-target');
zoomImgs.forEach(img => {
    img.addEventListener('click', function() {
        zoomTarget.src = this.src;
        overlay.classList.add('active');
    });
});
function closeImgZoom() {
    overlay.classList.remove('active');
    zoomTarget.src = '';
}
overlay.addEventListener('click', function(e) {
    if (e.target === overlay) closeImgZoom();
});
</script>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?> 