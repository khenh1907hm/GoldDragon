<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php
// Dữ liệu mẫu, sau này thay bằng dữ liệu từ database
$newsList = [
    [
        'img' => '/RongVang/asset/img/reason_1.jpg',
        'title' => 'Khai giảng năm học mới 2024-2025',
        'desc' => 'Ngày 5/9, trường Golden Dragon Kindergarten tưng bừng tổ chức lễ khai giảng với nhiều hoạt động hấp dẫn cho các bé.',
        'link' => '#'
    ],
    [
        'img' => '/RongVang/asset/img/reason_2.jpg',
        'title' => 'Ngày hội thể thao cho bé',
        'desc' => 'Các bé được tham gia nhiều trò chơi vận động, rèn luyện sức khỏe và tinh thần đồng đội trong ngày hội thể thao vừa qua.',
        'link' => '#'
    ],
    [
        'img' => '/RongVang/asset/img/reason_3.jpg',
        'title' => 'Workshop kỹ năng sống: Bé tự lập',
        'desc' => 'Chương trình giúp các bé học cách tự phục vụ bản thân, phát triển kỹ năng sống ngay từ nhỏ.',
        'link' => '#'
    ],
    [
        'img' => '/RongVang/asset/img/reason_4.jpg',
        'title' => 'Bé vui học toán',
        'desc' => 'Hoạt động toán học sáng tạo giúp bé phát triển tư duy logic và khả năng giải quyết vấn đề.',
        'link' => '#'
    ],
    [
        'img' => '/RongVang/asset/img/reason_5.jpg',
        'title' => 'Bé sáng tạo mỹ thuật',
        'desc' => 'Các bé được tự do sáng tạo với màu sắc, chất liệu, phát triển trí tưởng tượng phong phú.',
        'link' => '#'
    ],
    [
        'img' => '/RongVang/asset/img/reason_6.jpg',
        'title' => 'Khám phá khoa học',
        'desc' => 'Bé được tham gia các thí nghiệm vui, khám phá thế giới xung quanh một cách sinh động.',
        'link' => '#'
    ],
    [
        'img' => '/RongVang/asset/img/reason_7.jpg',
        'title' => 'Bé yêu thiên nhiên',
        'desc' => 'Hoạt động ngoài trời giúp bé gần gũi thiên nhiên, phát triển thể chất và tinh thần.',
        'link' => '#'
    ],
    [
        'img' => '/RongVang/asset/img/reason_8.jpg',
        'title' => 'Bé vui cùng âm nhạc',
        'desc' => 'Các tiết học âm nhạc giúp bé phát triển cảm xúc, khả năng cảm thụ nghệ thuật.',
        'link' => '#'
    ],
    [
        'img' => '/RongVang/asset/img/teacher1.jpg',
        'title' => 'Gặp gỡ giáo viên mới',
        'desc' => 'Chào mừng các giáo viên mới gia nhập đội ngũ Golden Dragon Kindergarten.',
        'link' => '#'
    ],
    [
        'img' => '/RongVang/asset/img/teacher2.jpg',
        'title' => 'Chương trình ngoại khóa tháng 6',
        'desc' => 'Nhiều hoạt động ngoại khóa hấp dẫn sẽ diễn ra trong tháng 6 này.',
        'link' => '#'
    ],
    [
        'img' => '/RongVang/asset/img/teacher3.jpg',
        'title' => 'Bé làm quen tiếng Anh',
        'desc' => 'Các tiết học tiếng Anh vui nhộn giúp bé tự tin giao tiếp.',
        'link' => '#'
    ],
    [
        'img' => '/RongVang/asset/img/logo.png',
        'title' => 'Thông báo nghỉ hè',
        'desc' => 'Nhà trường thông báo lịch nghỉ hè cho các bé và phụ huynh.',
        'link' => '#'
    ],
];
$perPage = 6;
$total = count($newsList);
$pageCount = ceil($total / $perPage);
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
if ($page > $pageCount) $page = $pageCount;
$start = ($page - 1) * $perPage;
$newsPage = array_slice($newsList, $start, $perPage);
?>
<main class="container mx-auto py-8">
    <h1 class="text-4xl md:text-5xl font-extrabold mb-6 md:mb-10 text-center text-green-700">Tin tức</h1>
    <h2 class="text-2xl md:text-3xl font-semibold mt-6 mb-8 text-center text-green-600 tracking-wide uppercase">Tin tức nhóm trẻ</h2>
    <div class="grid gap-8 grid-cols-1 md:grid-cols-2">
        <?php foreach ($newsPage as $news): ?>
        <div class="news-item bg-white rounded-xl shadow-lg overflow-hidden flex flex-col border border-green-100 hover:shadow-2xl transition-shadow">
            <img src="<?php echo htmlspecialchars($news['img']); ?>" alt="<?php echo htmlspecialchars($news['title']); ?>" class="w-full h-48 object-cover">
            <div class="p-6 flex flex-col justify-between flex-1">
                <div>
                    <h2 class="text-xl font-semibold text-green-800 mb-2"><?php echo htmlspecialchars($news['title']); ?></h2>
                    <p class="text-gray-700 text-base leading-relaxed mb-3"><?php echo htmlspecialchars($news['desc']); ?></p>
                </div>
                <a href="<?php echo htmlspecialchars($news['link']); ?>" class="text-green-600 font-medium hover:underline mt-auto">Xem chi tiết</a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <!-- Phân trang -->
    <div class="flex justify-center mt-8">
        <?php for ($i = 1; $i <= $pageCount; $i++): ?>
            <?php if ($i == $page): ?>
                <a href="#" class="mx-1 px-3 py-1 rounded font-medium bg-green-500 text-white pointer-events-none"><?php echo $i; ?></a>
            <?php else: ?>
                <a href="?page=<?php echo $i; ?>" class="mx-1 px-3 py-1 rounded font-medium bg-green-100 text-green-800 hover:bg-green-300"><?php echo $i; ?></a>
            <?php endif; ?>
        <?php endfor; ?>
    </div>
</main>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?> 