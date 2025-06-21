<?php
require_once __DIR__ . '/../../includes/Pagination.php';
require_once __DIR__ . '/../../models/Registration.php';
require_once __DIR__ . '/../../includes/Database.php';

// Khởi tạo model Registration với database connection mới
$db = Database::getInstance()->getConnection();
$registrationModel = new Registration($db);

// Get current page and filters
$currentPage = isset($_GET['page_num']) ? (int)$_GET['page_num'] : 1;
$currentPage = max(1, $currentPage);
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$status = isset($_GET['status']) ? trim($_GET['status']) : '';

// Get registrations with pagination and filters
$result = $registrationModel->read($currentPage, $search, $status);
$registrations = $result ? $result->fetchAll(PDO::FETCH_ASSOC) : [];

// Get pagination object
$pagination = $registrationModel->getPagination($currentPage, $search, $status);

// Get statistics
$stats = $registrationModel->getStats();
?>

<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Danh sách đăng ký nhập học</h1>
        <p class="text-gray-600 mt-1">Quản lý các đăng ký nhập học của học sinh</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-full">
                    <i class="fas fa-users text-blue-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Tổng đăng ký</p>
                    <p class="text-2xl font-bold text-gray-900"><?php echo $stats['total']; ?></p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-full">
                    <i class="fas fa-clock text-yellow-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Chờ xử lý</p>
                    <p class="text-2xl font-bold text-gray-900"><?php echo $stats['pending']; ?></p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-full">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Đã xử lý</p>
                    <p class="text-2xl font-bold text-gray-900"><?php echo $stats['approved']; ?></p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-full">
                    <i class="fas fa-calendar-day text-purple-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Hôm nay</p>
                    <p class="text-2xl font-bold text-gray-900"><?php echo $stats['today']; ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="mb-6 flex flex-wrap justify-between items-center">
        <div class="flex-1 max-w-sm mr-4">
            <form method="GET" action="index.php" class="relative">
                <input type="hidden" name="page" value="registrations">
                <?php if (!empty($status)): ?>
                    <input type="hidden" name="status" value="<?php echo htmlspecialchars($status); ?>">
                <?php endif; ?>
                <input type="text" 
                       name="search"
                       placeholder="Tìm kiếm theo tên, số điện thoại..." 
                       value="<?php echo htmlspecialchars($search); ?>"
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <?php if (!empty($search)): ?>
                    <a href="index.php?page=registrations<?php echo !empty($status) ? '&status=' . urlencode($status) : ''; ?>" 
                       class="absolute right-3 top-1/2 transform -translate-y-1/2 text-red-500 hover:text-red-700">
                        <i class="fas fa-times"></i>
                    </a>
                <?php endif; ?>
            </form>
        </div>
        <div class="flex gap-2 items-center">
            <form method="GET" action="index.php" class="flex items-center">
                <input type="hidden" name="page" value="registrations">
                <?php if (!empty($search)): ?>
                    <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">
                <?php endif; ?>
                <select name="status" 
                        class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        onchange="this.form.submit()">
                    <option value="all" <?php echo $status === 'all' || empty($status) ? 'selected' : ''; ?>>Tất cả trạng thái</option>
                    <option value="pending" <?php echo $status === 'pending' ? 'selected' : ''; ?>>Chờ xử lý</option>
                    <option value="approved" <?php echo $status === 'approved' ? 'selected' : ''; ?>>Đã xử lý</option>
                </select>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/4">Thông tin học sinh</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/5">Phụ huynh</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">Liên hệ</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/12">Trạng thái</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">Thời gian</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/12">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (empty($registrations)): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            <?php if (!empty($search) || !empty($status)): ?>
                                Không tìm thấy đăng ký nào phù hợp với tiêu chí tìm kiếm.
                            <?php else: ?>
                                Không tìm thấy đăng ký nào
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($registrations as $index => $registration): ?>
                        <tr class="hover:bg-gray-50 transition-colors" id="row-<?php echo $registration['id']; ?>">
                            <td class="px-6 py-4 text-sm text-gray-500 align-middle"><?php echo $index + 1; ?></td>
                            <td class="px-6 py-4 align-middle">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-child text-blue-500 text-lg"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-base font-medium text-gray-900">
                                            <?php echo htmlspecialchars($registration['student_name']); ?>
                                            <?php if($registration['nick_name']): ?>
                                                <span class="text-gray-500 italic">(<?php echo htmlspecialchars($registration['nick_name']); ?>)</span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="text-sm text-gray-500 mt-1">
                                            Tuổi: <?php echo htmlspecialchars($registration['age']); ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm align-middle">
                                <div class="text-gray-900 font-medium"><?php echo htmlspecialchars($registration['parent_name']); ?></div>
                                <div class="text-gray-500 text-xs mt-1"><?php echo htmlspecialchars($registration['address']); ?></div>
                            </td>
                            <td class="px-6 py-4 text-sm align-middle">
                                <div class="flex flex-col space-y-1">
                                    <a href="tel:<?php echo $registration['phone']; ?>" 
                                       class="text-blue-600 hover:text-blue-800 flex items-center">
                                        <i class="fas fa-phone mr-2 text-xs"></i>
                                        <?php echo htmlspecialchars($registration['phone']); ?>
                                    </a>
                                    <a href="https://zalo.me/<?php echo $registration['phone']; ?>" 
                                       class="text-green-600 hover:text-green-800 flex items-center"
                                       title="Nhắn tin Zalo">
                                        <i class="fab fa-facebook-messenger mr-2 text-xs"></i>
                                        Zalo
                                    </a>
                                </div>
                            </td>
                            <td class="px-6 py-4 align-middle">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold <?php 
                                    echo $registration['status'] === 'pending' ? 'bg-yellow-100 text-yellow-700' : 
                                        ($registration['status'] === 'approved' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'); 
                                ?>">
                                    <?php echo ucfirst(htmlspecialchars($registration['status'])); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 align-middle">
                                <?php echo date('d/m/Y H:i', strtotime($registration['created_at'])); ?>
                            </td>
                            <td class="px-6 py-4 align-middle">
                                <div class="flex items-center space-x-1">
                                    <button onclick="showDetails(<?php echo $registration['id']; ?>, '<?php echo htmlspecialchars(addslashes($registration['content'])); ?>')" 
                                            class="inline-flex items-center justify-center px-3 py-2 border border-blue-500 text-sm font-medium rounded-md text-blue-600 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                            title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <form action="index.php?action=registrations&op=update-status" method="POST" class="inline">
                                        <input type="hidden" name="id" value="<?php echo $registration['id']; ?>">
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit" 
                                                onclick="return markAsRead(<?php echo $registration['id']; ?>)"
                                                class="inline-flex items-center justify-center px-3 py-2 border border-green-500 text-sm font-medium rounded-md text-green-600 bg-white hover:bg-green-50 <?php echo $registration['status'] === 'approved' ? 'opacity-50 cursor-not-allowed' : ''; ?> focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                                                <?php echo $registration['status'] === 'approved' ? 'disabled' : ''; ?>
                                                title="<?php echo $registration['status'] === 'approved' ? 'Đã xử lý' : 'Đánh dấu đã đọc'; ?>">
                                            <i class="fas fa-check-circle"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <?php if ($pagination->getTotalPages() > 1): ?>
        <div class="mt-6">
            <?php 
            // Convert Tailwind pagination to Bootstrap style for consistency
            $paginationHtml = $pagination->render([
                'showInfo' => true,
                'showFirstLast' => true,
                'showPrevNext' => true,
                'maxVisible' => 5,
                'alignment' => 'center'
            ]);
            
            // Replace Bootstrap classes with Tailwind classes
            $paginationHtml = str_replace('pagination', 'flex justify-center', $paginationHtml);
            $paginationHtml = str_replace('page-item', 'mx-1', $paginationHtml);
            $paginationHtml = str_replace('page-link', 'px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50', $paginationHtml);
            $paginationHtml = str_replace('active', 'bg-blue-500 text-white border-blue-500', $paginationHtml);
            $paginationHtml = str_replace('disabled', 'opacity-50 cursor-not-allowed', $paginationHtml);
            
            echo $paginationHtml;
            ?>
        </div>
    <?php endif; ?>
</div>

<!-- Alert Box for Details -->
<div id="detailsAlert" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-900">Chi tiết đăng ký</h3>
                <button onclick="hideDetails()" class="text-gray-400 hover:text-gray-500 text-lg">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="detailsContent" class="text-gray-700 text-base whitespace-pre-wrap leading-relaxed"></div>
            <div class="mt-6 flex justify-end">
                <button onclick="hideDetails()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors text-sm font-medium">
                    Đóng
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Show details in alert box
function showDetails(id, content) {
    const alert = document.getElementById('detailsAlert');
    const contentDiv = document.getElementById('detailsContent');
    contentDiv.textContent = content;
    alert.classList.remove('hidden');
    alert.classList.add('flex');
}

// Hide details alert box
function hideDetails() {
    const alert = document.getElementById('detailsAlert');
    alert.classList.add('hidden');
    alert.classList.remove('flex');
}

// Mark as read and remove row
function markAsRead(id) {
    if (confirm('Bạn có chắc muốn đánh dấu đã đọc và xóa đăng ký này?')) {
        const row = document.getElementById('row-' + id);
        if (row) {
            row.style.transition = 'opacity 0.3s ease-out';
            row.style.opacity = '0';
            setTimeout(() => {
                row.remove();
            }, 300);
        }
        return true;
    }
    return false;
}
</script>
