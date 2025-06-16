<?php 
require_once __DIR__ . '/../../models/Menu.php';

$menuModel = new Menu();

// Get week and year from URL parameters or use current week
$week = isset($_GET['week']) ? (int)$_GET['week'] : null;
$year = isset($_GET['year']) ? (int)$_GET['year'] : null;

if ($week && $year) {
    $currentWeekMenu = $menuModel->getMenuByWeek($week, $year);
    $weekInfo = [
        'week' => $week,
        'year' => $year,
        'is_next_week' => false
    ];
} else {
    $currentWeekMenu = $menuModel->getCurrentWeekMenu();
    $weekInfo = $menuModel->getCurrentWeekInfo();
}

$pageTitle = 'Thực đơn';
require_once __DIR__ . '/../layouts/header.php'; 
?>

<main class="container mx-auto py-10 px-2 min-h-[60vh] flex flex-col items-center bg-gray-50">
    <h1 class="text-3xl md:text-4xl font-extrabold text-pink-600 mb-4 text-center tracking-wide">
        <?php echo $weekInfo['is_next_week'] ? 'Thực đơn tuần sau' : 'Thực đơn tuần này'; ?>
    </h1>
    <p class="text-gray-600 mb-8 text-center">
        Tuần <?php echo $weekInfo['week']; ?> (<?php echo date('d/m/Y', strtotime($weekInfo['start_date'])); ?> - <?php echo date('d/m/Y', strtotime($weekInfo['end_date'])); ?>)
    </p>
    
    <!-- Add week navigation -->
    <div class="mb-6 flex gap-4">
        <?php
        $currentWeek = $weekInfo['week'];
        $currentYear = $weekInfo['year'];
        $prevWeek = $currentWeek - 1;
        $nextWeek = $currentWeek + 1;
        ?>
        <a href="?page=thuc-don&week=<?php echo $prevWeek; ?>&year=<?php echo $currentYear; ?>" 
           class="px-4 py-2 bg-pink-100 text-pink-600 rounded-lg hover:bg-pink-200 transition">
            Tuần trước
        </a>
        <a href="?page=thuc-don&week=<?php echo $nextWeek; ?>&year=<?php echo $currentYear; ?>" 
           class="px-4 py-2 bg-pink-100 text-pink-600 rounded-lg hover:bg-pink-200 transition">
            Tuần sau
        </a>
    </div>

    <?php if ($currentWeekMenu): ?>
    <div class="w-full max-w-3xl overflow-x-auto">
        <table class="min-w-full bg-white rounded-2xl shadow-xl border border-pink-100">
            <thead>
                <tr>
                    <th class="py-3 px-4 bg-pink-100 text-pink-800 text-lg font-bold text-center">Thứ</th>
                    <th class="py-3 px-4 bg-pink-100 text-pink-800 text-lg font-bold text-center">Bữa sáng</th>
                    <th class="py-3 px-4 bg-pink-100 text-pink-800 text-lg font-bold text-center">Bữa trưa</th>
                    <th class="py-3 px-4 bg-pink-100 text-pink-800 text-lg font-bold text-center">Bữa chiều</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 text-base">
                <?php
                $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
                $dayNames = ['Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6'];
                foreach ($days as $index => $day):
                    if (isset($currentWeekMenu[$day])):
                ?>
                <tr class="hover:bg-pink-50 transition">
                    <td class="py-3 px-4 font-semibold text-center"><?php echo $dayNames[$index]; ?></td>
                    <td class="py-3 px-4 text-center"><?php echo htmlspecialchars($currentWeekMenu[$day]['breakfast']); ?></td>
                    <td class="py-3 px-4 text-center"><?php echo htmlspecialchars($currentWeekMenu[$day]['lunch']); ?></td>
                    <td class="py-3 px-4 text-center"><?php echo htmlspecialchars($currentWeekMenu[$day]['snack']); ?></td>
                </tr>
                <?php
                    endif;
                endforeach;
                ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="text-center text-gray-600">
        <p class="text-lg">Chưa có thực đơn cho <?php echo $weekInfo['is_next_week'] ? 'tuần sau' : 'tuần này'; ?>.</p>
        <p class="text-sm mt-2">(Tuần <?php echo $weekInfo['week']; ?>, từ <?php echo date('d/m/Y', strtotime($weekInfo['start_date'])); ?> đến <?php echo date('d/m/Y', strtotime($weekInfo['end_date'])); ?>)</p>
    </div>
    <?php endif; ?>
</main>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?> 