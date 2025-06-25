<?php 
$pageTitle = 'Bản tin';
require_once __DIR__ . '/../layouts/header.php'; 

// Debug mode
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Kết nối DB và lấy danh sách bài viết (posts)
require_once __DIR__ . '/../../models/Post.php';
$postModel = new Post();

// Debug: Kiểm tra kết nối và dữ liệu
try {
    $total = $postModel->count();
    echo "<!-- Debug: Tổng số bài viết: " . $total . " -->";
    
    $posts = $postModel->getAll($page)->fetchAll(PDO::FETCH_ASSOC);
    echo "<!-- Debug: Số bài viết lấy được: " . count($posts) . " -->";
} catch (Exception $e) {
    echo "<!-- Debug Error: " . $e->getMessage() . " -->";
}

// Phân trang
$perPage = 8;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$total = $postModel->count();
$pageCount = ceil($total / $perPage);
if ($page > $pageCount) $page = $pageCount;

// Lấy bài viết cho trang hiện tại
$posts = $postModel->getAll($page)->fetchAll(PDO::FETCH_ASSOC);
?>
<main class="container mx-auto py-8">
    <h1 class="animate" data-animation="fade-in-up" style="font-size:2rem;font-weight:bold;margin-bottom:2rem;text-align:center;color:#1976D2;">Bản tin</h1>
    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        <?php foreach ($posts as $post): ?>
        <div class="bulletin-item bg-white rounded-xl shadow-lg p-6 flex flex-col gap-2 border border-blue-100 hover:shadow-2xl transition-shadow">
            <h2 class="text-xl font-semibold text-blue-800 mb-2"><?php echo htmlspecialchars($post['title']); ?></h2>
            <p class="text-gray-700 text-base leading-relaxed mb-1"><?php echo mb_strimwidth(strip_tags($post['content']), 0, 120, '...'); ?></p>
            <span class="date text-sm text-gray-500 italic">Đăng ngày: <?php echo date('d/m/Y', strtotime($post['created_at'])); ?></span>
        </div>
        <?php endforeach; ?>
        <?php if (empty($posts)): ?>
            <div class="col-span-full text-center text-gray-400 py-12">Chưa có bài viết nào.</div>
        <?php endif; ?>
    </div>
    <!-- Phân trang -->
    <?php if ($pageCount > 1): ?>
    <div class="flex justify-center mt-8">
        <?php for ($i = 1; $i <= $pageCount; $i++): ?>
            <?php if ($i == $page): ?>
                <a href="#" class="mx-1 px-3 py-1 rounded font-medium bg-blue-500 text-white pointer-events-none"><?php echo $i; ?></a>
            <?php else: ?>
                <a href="?page=<?php echo $i; ?>" class="mx-1 px-3 py-1 rounded font-medium bg-blue-100 text-blue-800 hover:bg-blue-300"><?php echo $i; ?></a>
            <?php endif; ?>
        <?php endfor; ?>
    </div>
    <?php endif; ?>
</main>
<div class="lg:w-1/3">
    <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
        <!-- Bài viết mới -->
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
                        <?php if ($recentPost['image']): ?>
                        <img src="/RongVang/<?php echo htmlspecialchars($recentPost['image']); ?>" 
                             alt="<?php echo htmlspecialchars($recentPost['title']); ?>"
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        <?php else: ?>
                        <img src="/RongVang/asset/img/logo.png" 
                             alt="<?php echo htmlspecialchars($recentPost['title']); ?>"
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        <?php endif; ?>
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
        <div class="border-t border-gray-200 my-6"></div>
        <!-- Thông tin liên hệ -->
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
                <span>616 Đ. Nguyễn Ảnh Thủ, Tân Chánh Hiệp, Quận 12, Hồ Chí Minh, Việt Nam</span>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?> 