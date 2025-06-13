<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php
// Kết nối DB và lấy danh sách bài viết (posts)
require_once __DIR__ . '/../../models/Post.php';
require_once __DIR__ . '/../../config/database.php';
$database = Database::getInstance();
$db = $database->connect();
$postModel = new Post($db);

// Phân trang
$perPage = 8;
$total = $postModel->count();
$pageCount = ceil($total / $perPage);
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
if ($page > $pageCount) $page = $pageCount;
$start = ($page - 1) * $perPage;

// Lấy bài viết cho trang hiện tại
$stmt = $db->prepare("SELECT * FROM posts ORDER BY created_at DESC LIMIT :start, :perPage");
$stmt->bindValue(':start', $start, PDO::PARAM_INT);
$stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<main class="container mx-auto py-8">
    <h1 class="text-3xl font-bold mb-8 text-center text-blue-700">Bản tin</h1>
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
<?php require_once __DIR__ . '/../layouts/footer.php'; ?> 