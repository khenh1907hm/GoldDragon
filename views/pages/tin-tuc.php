<?php 
$pageTitle = 'Tin tức';
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
$perPage = 6;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$total = $postModel->count();
$pageCount = ceil($total / $perPage);
if ($page > $pageCount) $page = $pageCount;

// Lấy bài viết cho trang hiện tại
$posts = $postModel->getAll($page)->fetchAll(PDO::FETCH_ASSOC);

// Lấy bài viết mới nhất cho sidebar
$recentPosts = $postModel->getRecent(3);
?>
<main class="container mx-auto py-8 px-4">
    <!-- Hero Section -->
    <div class="text-center mb-12 animate-fade-in">
        <h1 class="text-4xl md:text-5xl font-extrabold mb-4 text-green-700 bg-clip-text text-transparent bg-gradient-to-r from-green-600 to-green-800">Tin tức</h1>
        <h2 class="text-2xl md:text-3xl font-semibold text-green-600 tracking-wide uppercase">Tin tức nhóm trẻ</h2>
        <div class="w-24 h-1 bg-green-500 mx-auto mt-4 rounded-full"></div>
    </div>
    
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Main Content -->
        <div class="lg:w-2/3 lg:border-r lg:border-gray-200 lg:pr-8">
            <div class="grid gap-8 grid-cols-1 md:grid-cols-2">
                <?php foreach ($posts as $post): ?>
                <div class="news-item bg-white rounded-xl shadow-lg overflow-hidden flex flex-col border border-green-100 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="relative overflow-hidden group">
                        <?php if ($post['image']): ?>
                        <img src="/RongVang/<?php echo htmlspecialchars($post['image']); ?>" 
                             alt="<?php echo htmlspecialchars($post['title']); ?>" 
                             class="w-full h-48 object-cover transition-transform duration-500 group-hover:scale-110">
                        <?php else: ?>
                        <img src="/RongVang/asset/img/logo.png" 
                             alt="<?php echo htmlspecialchars($post['title']); ?>" 
                             class="w-full h-48 object-cover transition-transform duration-500 group-hover:scale-110">
                        <?php endif; ?>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </div>
                    <div class="p-6 flex flex-col justify-between flex-1">
                        <div>
                            <h2 class="text-xl font-semibold text-green-800 mb-2 hover:text-green-600 transition-colors duration-300">
                                <?php echo htmlspecialchars($post['title']); ?>
                            </h2>
                            <p class="text-gray-700 text-base leading-relaxed mb-3">
                                <?php echo mb_strimwidth(strip_tags($post['content']), 0, 120, '...'); ?>
                            </p>
                        </div>
                        <div class="flex items-center justify-between mt-4">
                            <span class="text-sm text-gray-500">
                                <i class="far fa-calendar-alt mr-1"></i>
                                <?php echo date('d/m/Y', strtotime($post['created_at'])); ?>
                            </span>
                            <a href="/RongVang/ban-tin/<?php echo $post['id']; ?>" 
                               class="inline-flex items-center text-green-600 font-medium hover:text-green-700 transition-colors duration-300">
                                Xem chi tiết
                                <i class="fas fa-arrow-right ml-2 transform transition-transform duration-300 group-hover:translate-x-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php if (empty($posts)): ?>
                    <div class="col-span-full text-center text-gray-400 py-12">
                        <i class="fas fa-newspaper text-4xl mb-4"></i>
                        <p>Chưa có bài viết nào.</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Phân trang -->
            <?php if ($pageCount > 1): ?>
            <div class="flex justify-center mt-12">
                <div class="flex space-x-2">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?php echo $page - 1; ?>" 
                           class="px-4 py-2 rounded-lg bg-green-100 text-green-800 hover:bg-green-200 transition-colors duration-300">
                            <i class="fas fa-chevron-left mr-1"></i> Trang trước
                        </a>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $pageCount; $i++): ?>
                        <?php if ($i == $page): ?>
                            <span class="px-4 py-2 rounded-lg bg-green-500 text-white cursor-default">
                                <?php echo $i; ?>
                            </span>
                        <?php else: ?>
                            <a href="?page=<?php echo $i; ?>" 
                               class="px-4 py-2 rounded-lg bg-green-100 text-green-800 hover:bg-green-200 transition-colors duration-300">
                                <?php echo $i; ?>
                            </a>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <?php if ($page < $pageCount): ?>
                        <a href="?page=<?php echo $page + 1; ?>" 
                           class="px-4 py-2 rounded-lg bg-green-100 text-green-800 hover:bg-green-200 transition-colors duration-300">
                            Trang sau <i class="fas fa-chevron-right ml-1"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="lg:w-1/3 space-y-8">
            <!-- Bài viết mới -->
            <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
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
.animate-fade-in {
    animation: fadeIn 0.8s ease-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?> 