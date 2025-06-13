<?php
$registrations = $registrationController->getAll();
ob_start();
?>
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-2xl font-bold text-blue-800 flex items-center gap-2">
            <i class="fas fa-clipboard-list"></i> Danh sách đăng ký nhập học
        </h2>
        <div class="flex items-center gap-4">
            <div class="view-toggle flex items-center bg-gray-100 rounded-lg p-1">
                <button onclick="switchView('card')" class="view-btn active p-2 rounded-lg hover:bg-white transition-colors" data-view="card">
                    <i class="fas fa-th-large"></i>
                </button>
                <button onclick="switchView('table')" class="view-btn p-2 rounded-lg hover:bg-white transition-colors" data-view="table">
                    <i class="fas fa-list"></i>
                </button>
            </div>
            <div class="relative">
                <input type="text" id="searchInput" placeholder="Tìm kiếm..." 
                       class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
        </div>
    </div>

    <!-- Card View -->
    <div id="cardView" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($registrations as $registration): ?>
        <div class="registration-card rounded-2xl shadow-lg border-2 <?php echo $registration['status'] === 'approved' ? 'border-green-400' : 'border-gray-200'; ?> bg-white flex flex-col h-full transform transition-all duration-300 hover:scale-[1.02] hover:shadow-xl">
            <div class="p-6 flex-1 flex flex-col">
                <div class="flex items-center justify-between mb-4">
                    <h5 class="text-lg font-bold text-blue-700">
                        <i class="fas fa-user-circle mr-2"></i>
                        <?php echo htmlspecialchars($registration['parent_name']); ?>
                    </h5>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold <?php 
                        echo $registration['status'] === 'pending' ? 'bg-yellow-100 text-yellow-700' : 
                            ($registration['status'] === 'approved' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'); 
                    ?>">
                        <?php echo ucfirst(htmlspecialchars($registration['status'])); ?>
                    </span>
                </div>

                <div class="space-y-3 mb-4">
                    <div class="flex items-center">
                        <i class="fas fa-child text-pink-500 w-6"></i>
                        <span class="ml-2">
                            <span class="font-medium">Bé:</span> 
                            <?php echo htmlspecialchars($registration['student_name']); ?>
                            <?php if($registration['nick_name']): ?>
                                <span class="text-gray-500 italic">(<?php echo htmlspecialchars($registration['nick_name']); ?>)</span>
                            <?php endif; ?>
                        </span>
                    </div>

                    <div class="flex items-center">
                        <i class="fas fa-birthday-cake text-yellow-500 w-6"></i>
                        <span class="ml-2">
                            <span class="font-medium">Tuổi:</span> 
                            <?php echo htmlspecialchars($registration['age']); ?>
                        </span>
                    </div>

                    <div class="flex items-center">
                        <i class="fas fa-phone text-green-500 w-6"></i>
                        <a href="tel:<?php echo $registration['phone']; ?>" class="ml-2 hover:text-blue-600 transition-colors">
                            <span class="font-medium">SĐT:</span> 
                            <?php echo htmlspecialchars($registration['phone']); ?>
                        </a>
                    </div>

                    <div class="flex items-center">
                        <i class="fas fa-map-marker-alt text-red-500 w-6"></i>
                        <span class="ml-2">
                            <span class="font-medium">Địa chỉ:</span> 
                            <?php echo htmlspecialchars($registration['address']); ?>
                        </span>
                    </div>
                </div>

                <div class="mb-4 bg-gray-50 rounded-lg p-3">
                    <div class="font-medium text-gray-700 mb-1">Nội dung:</div>
                    <div class="text-gray-600 text-sm">
                        <?php echo nl2br(htmlspecialchars($registration['content'])); ?>
                    </div>
                </div>

                <div class="mt-auto pt-4 border-t border-gray-100">
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-gray-400">
                            <i class="far fa-clock mr-1"></i>
                            <?php echo date('d/m/Y H:i', strtotime($registration['created_at'])); ?>
                        </span>
                        <div class="flex gap-2">
                            <a href="tel:<?php echo $registration['phone']; ?>" 
                               class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors" 
                               title="Gọi điện">
                                <i class="fas fa-phone"></i>
                            </a>
                            <a href="https://zalo.me/<?php echo $registration['phone']; ?>" 
                               class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" 
                               title="Nhắn tin Zalo">
                                <i class="fab fa-facebook-messenger"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-4 border-t bg-gray-50 rounded-b-2xl">
                <form action="index.php?action=registrations&op=update-status" method="POST" class="m-0">
                    <input type="hidden" name="id" value="<?php echo $registration['id']; ?>">
                    <input type="hidden" name="status" value="approved">
                    <button type="submit" 
                            class="w-full px-4 py-2 rounded-lg font-bold text-white bg-green-500 hover:bg-green-600 shadow transition disabled:opacity-60 disabled:cursor-not-allowed flex items-center justify-center gap-2" 
                            <?php echo $registration['status'] === 'approved' ? 'disabled' : ''; ?>>
                        <i class="fas fa-check-circle"></i>
                        <?php echo $registration['status'] === 'approved' ? 'Đã xử lý' : 'Đánh dấu đã đọc'; ?>
                    </button>
                </form>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Table View -->
    <div id="tableView" class="hidden">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phụ huynh</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bé</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tuổi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Liên hệ</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thời gian</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($registrations as $registration): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <i class="fas fa-user-circle text-blue-500 mr-2"></i>
                                    <span class="font-medium text-gray-900"><?php echo htmlspecialchars($registration['parent_name']); ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <?php echo htmlspecialchars($registration['student_name']); ?>
                                    <?php if($registration['nick_name']): ?>
                                        <span class="text-gray-500 italic">(<?php echo htmlspecialchars($registration['nick_name']); ?>)</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900"><?php echo htmlspecialchars($registration['age']); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm">
                                    <a href="tel:<?php echo $registration['phone']; ?>" class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-phone mr-1"></i><?php echo htmlspecialchars($registration['phone']); ?>
                                    </a>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold <?php 
                                    echo $registration['status'] === 'pending' ? 'bg-yellow-100 text-yellow-700' : 
                                        ($registration['status'] === 'approved' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'); 
                                ?>">
                                    <?php echo ucfirst(htmlspecialchars($registration['status'])); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <i class="far fa-clock mr-1"></i>
                                <?php echo date('d/m/Y H:i', strtotime($registration['created_at'])); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center gap-2">
                                    <a href="tel:<?php echo $registration['phone']; ?>" 
                                       class="text-green-600 hover:text-green-800" 
                                       title="Gọi điện">
                                        <i class="fas fa-phone"></i>
                                    </a>
                                    <a href="https://zalo.me/<?php echo $registration['phone']; ?>" 
                                       class="text-blue-600 hover:text-blue-800" 
                                       title="Nhắn tin Zalo">
                                        <i class="fab fa-facebook-messenger"></i>
                                    </a>
                                    <button type="button" 
                                            class="text-gray-600 hover:text-gray-800" 
                                            onclick="showContent(<?php echo $registration['id']; ?>)"
                                            title="Xem nội dung">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <form action="index.php?action=registrations&op=update-status" method="POST" class="inline">
                                        <input type="hidden" name="id" value="<?php echo $registration['id']; ?>">
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit" 
                                                class="text-green-600 hover:text-green-800 <?php echo $registration['status'] === 'approved' ? 'opacity-50 cursor-not-allowed' : ''; ?>"
                                                <?php echo $registration['status'] === 'approved' ? 'disabled' : ''; ?>
                                                title="<?php echo $registration['status'] === 'approved' ? 'Đã xử lý' : 'Đánh dấu đã đọc'; ?>">
                                            <i class="fas fa-check-circle"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php if (empty($registrations)): ?>
    <div class="col-span-full">
        <div class="bg-gradient-to-br from-blue-50 via-white to-pink-50 rounded-3xl p-8 shadow-lg text-center">
            <div class="bg-white rounded-full w-24 h-24 flex items-center justify-center mx-auto mb-6 shadow-md">
                <i class="fas fa-child text-4xl text-blue-500"></i>
            </div>
            <h3 class="text-2xl font-bold text-blue-600 mb-3">Chưa có đăng ký nào</h3>
            <p class="text-gray-600 max-w-md mx-auto">
                Hiện tại chưa có bé nào đăng ký nhập học.<br>
                Hãy chờ đợi những hồ sơ đầu tiên với nhiều niềm vui và hy vọng nhé!
            </p>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Modal for content -->
<div id="contentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-900">Nội dung đăng ký</h3>
                <button onclick="hideContent()" class="text-gray-400 hover:text-gray-500">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="modalContent" class="text-gray-600"></div>
        </div>
    </div>
</div>

<script>
// View switching
function switchView(view) {
    const cardView = document.getElementById('cardView');
    const tableView = document.getElementById('tableView');
    const buttons = document.querySelectorAll('.view-btn');
    
    buttons.forEach(btn => {
        btn.classList.remove('active', 'bg-white');
        if (btn.dataset.view === view) {
            btn.classList.add('active', 'bg-white');
        }
    });
    
    if (view === 'card') {
        cardView.classList.remove('hidden');
        tableView.classList.add('hidden');
    } else {
        cardView.classList.add('hidden');
        tableView.classList.remove('hidden');
    }
}

// Search functionality
document.getElementById('searchInput').addEventListener('keyup', function() {
    const searchText = this.value.toLowerCase();
    const cards = document.querySelectorAll('.registration-card');
    const rows = document.querySelectorAll('tbody tr');
    
    cards.forEach(card => {
        const text = card.textContent.toLowerCase();
        card.style.display = text.includes(searchText) ? '' : 'none';
    });
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchText) ? '' : 'none';
    });
});

// Content modal
function showContent(id) {
    const modal = document.getElementById('contentModal');
    const content = document.getElementById('modalContent');
    const registration = <?php echo json_encode($registrations); ?>.find(r => r.id === id);
    
    if (registration) {
        content.innerHTML = registration.content.replace(/\n/g, '<br>');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
}

function hideContent() {
    const modal = document.getElementById('contentModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// Close modal when clicking outside
document.getElementById('contentModal').addEventListener('click', function(e) {
    if (e.target === this) {
        hideContent();
    }
});
</script>

<style>
/* Custom scrollbar */
::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Smooth transitions */
.transition-all {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 300ms;
}

/* Card hover effect */
.hover\:scale-\[1\.02\]:hover {
    transform: scale(1.02);
}

/* Button hover effect */
.hover\:bg-green-600:hover {
    background-color: #059669;
}

/* Icon animations */
.fas, .fab {
    transition: transform 0.2s;
}

a:hover .fas,
a:hover .fab {
    transform: scale(1.1);
}

/* View toggle */
.view-toggle {
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.view-btn {
    color: #6B7280;
}

.view-btn.active {
    color: #3B82F6;
    background-color: white;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

/* Table styles */
.table-container {
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

/* Modal animation */
.modal {
    transition: opacity 0.3s ease-in-out;
}

.modal-content {
    transform: scale(0.95);
    transition: transform 0.3s ease-in-out;
}

.modal.show .modal-content {
    transform: scale(1);
}
</style>

<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
