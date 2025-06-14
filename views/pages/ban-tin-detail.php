<?php 
$pageTitle = 'Chi tiết bài viết';
require_once __DIR__ . '/../layouts/header.php'; 

// Lấy ID bài viết từ URL
$postId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Kết nối DB và lấy thông tin bài viết
require_once __DIR__ . '/../../models/Post.php';
$postModel = new Post();
$post = $postModel->getById($postId);

// Nếu không tìm thấy bài viết, chuyển hướng về trang bản tin
if (!$post) {
    header('Location: /RongVang/ban-tin');
    exit;
}

// Lấy các bài viết liên quan (cùng loại hoặc mới nhất)
$relatedPosts = $postModel->getRecent(3);
?>

<main class="container mx-auto py-8 px-4">
    <!-- Breadcrumb -->
    <nav class="flex mb-8 text-sm">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="/RongVang" class="text-gray-700 hover:text-green-600">
                    <i class="fas fa-home mr-2"></i>Trang chủ
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <a href="/RongVang/ban-tin" class="text-gray-700 hover:text-green-600">Bản tin</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <span class="text-gray-500"><?php echo htmlspecialchars($post['title']); ?></span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <article class="bg-white rounded-xl shadow-lg overflow-hidden">
                <?php if ($post['image']): ?>
                <div class="relative">
                    <img src="/RongVang/<?php echo htmlspecialchars($post['image']); ?>" 
                         alt="<?php echo htmlspecialchars($post['title']); ?>" 
                         class="w-full h-[400px] object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                </div>
                <?php endif; ?>
                
                <div class="p-8">
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                        <?php echo htmlspecialchars($post['title']); ?>
                    </h1>
                    
                    <div class="flex items-center text-gray-600 mb-6">
                        <span class="flex items-center mr-4">
                            <i class="far fa-calendar-alt mr-2"></i>
                            <?php echo date('d/m/Y', strtotime($post['created_at'])); ?>
                        </span>
                        <span class="flex items-center">
                            <i class="far fa-clock mr-2"></i>
                            <?php echo date('H:i', strtotime($post['created_at'])); ?>
                        </span>
                    </div>

                    <div class="prose prose-lg max-w-none">
                        <?php echo $post['content']; ?>
                    </div>

                    <!-- Tags -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <div class="flex flex-wrap gap-2">
                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">
                                <i class="fas fa-tag mr-1"></i>Tin tức
                            </span>
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                                <i class="fas fa-tag mr-1"></i>Hoạt động
                            </span>
                        </div>
                    </div>

                    <!-- Share Buttons -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <h3 class="text-lg font-semibold mb-4">Chia sẻ bài viết</h3>
                        <div class="flex space-x-4">
                            <a href="#" class="text-blue-600 hover:text-blue-800">
                                <i class="fab fa-facebook-f text-xl"></i>
                            </a>
                            <a href="#" class="text-pink-600 hover:text-pink-800">
                                <i class="fab fa-instagram text-xl"></i>
                            </a>
                            <a href="#" class="text-blue-400 hover:text-blue-600">
                                <i class="fab fa-twitter text-xl"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </article>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Related Posts -->
            <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Bài viết liên quan</h3>
                <div class="space-y-4">
                    <?php foreach ($relatedPosts as $relatedPost): ?>
                        <?php if ($relatedPost['id'] != $post['id']): ?>
                        <a href="/RongVang/ban-tin/<?php echo $relatedPost['id']; ?>" 
                           class="block group">
                            <div class="flex items-start space-x-4">
                                <?php if ($relatedPost['image']): ?>
                                <img src="/RongVang/<?php echo htmlspecialchars($relatedPost['image']); ?>" 
                                     alt="<?php echo htmlspecialchars($relatedPost['title']); ?>"
                                     class="w-20 h-20 object-cover rounded-lg">
                                <?php else: ?>
                                <img src="/RongVang/asset/img/logo.png" 
                                     alt="<?php echo htmlspecialchars($relatedPost['title']); ?>"
                                     class="w-20 h-20 object-cover rounded-lg">
                                <?php endif; ?>
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-900 group-hover:text-green-600">
                                        <?php echo htmlspecialchars($relatedPost['title']); ?>
                                    </h4>
                                    <p class="text-xs text-gray-500 mt-1">
                                        <?php echo date('d/m/Y', strtotime($relatedPost['created_at'])); ?>
                                    </p>
                                </div>
                            </div>
                        </a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Liên hệ</h3>
                <div class="space-y-4">
                    <div class="flex items-center text-gray-600">
                        <i class="fas fa-phone-alt w-6"></i>
                        <span>0839395292</span>
                    </div>
                    <div class="flex items-center text-gray-600">
                        <i class="fas fa-envelope w-6"></i>
                        <span>info@goldendragon.edu.vn</span>
                    </div>
                    <div class="flex items-center text-gray-600">
                        <i class="fas fa-map-marker-alt w-6"></i>
                        <span>15 Đông Đô, Thị trấn Liên Nghĩa, Đức Trọng, Lâm Đồng</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?> 