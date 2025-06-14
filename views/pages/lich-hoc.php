<?php 
$pageTitle = 'Lịch học';
require_once __DIR__ . '/../layouts/header.php'; ?>
<main class="container mx-auto py-8">
    <h1 class="text-3xl font-bold mb-6 text-center text-yellow-700">Lịch học tuần này</h1>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white shadow-md rounded-lg">
            <thead>
                <tr>
                    <th class="py-3 px-6 bg-yellow-200 text-yellow-900 text-left">Thứ</th>
                    <th class="py-3 px-6 bg-yellow-200 text-yellow-900 text-left">Hoạt động chính</th>
                </tr>
            </thead>
            <tbody id="schedule-body">
                <!-- Nội dung sẽ được render bằng JS -->
            </tbody>
        </table>
    </div>
    <div class="flex justify-center mt-6" id="pagination">
        <!-- Pagination buttons will be rendered here -->
    </div>
</main>
<script>
// Dữ liệu mẫu 15 dòng
const scheduleData = [
    { day: 'Thứ 2', activity: 'Khám phá khoa học' },
    { day: 'Thứ 3', activity: 'Âm nhạc & Vận động' },
    { day: 'Thứ 4', activity: 'Mỹ thuật sáng tạo' },
    { day: 'Thứ 5', activity: 'Toán học vui nhộn' },
    { day: 'Thứ 6', activity: 'Hoạt động ngoài trời' },
    { day: 'Thứ 2', activity: 'Kể chuyện sáng tạo' },
    { day: 'Thứ 3', activity: 'Thí nghiệm vui' },
    { day: 'Thứ 4', activity: 'Vẽ tranh tập thể' },
    { day: 'Thứ 5', activity: 'Trò chơi vận động' },
    { day: 'Thứ 6', activity: 'Dã ngoại sân trường' },
    { day: 'Thứ 2', activity: 'Làm quen tiếng Anh' },
    { day: 'Thứ 3', activity: 'Nhảy múa sáng tạo' },
    { day: 'Thứ 4', activity: 'Xây dựng nhóm' },
    { day: 'Thứ 5', activity: 'Khám phá thiên nhiên' },
    { day: 'Thứ 6', activity: 'Ngày hội vui vẻ' },
];
const rowsPerPage = 5;
let currentPage = 1;

function renderTable(page) {
    const tbody = document.getElementById('schedule-body');
    tbody.innerHTML = '';
    const start = (page - 1) * rowsPerPage;
    const end = start + rowsPerPage;
    const pageData = scheduleData.slice(start, end);
    pageData.forEach(row => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td class="py-2 px-6 border-b border-gray-200">${row.day}</td>
            <td class="py-2 px-6 border-b border-gray-200">${row.activity}</td>
        `;
        tbody.appendChild(tr);
    });
}

function renderPagination() {
    const pageCount = Math.ceil(scheduleData.length / rowsPerPage);
    const pagination = document.getElementById('pagination');
    pagination.innerHTML = '';
    for (let i = 1; i <= pageCount; i++) {
        const btn = document.createElement('button');
        btn.textContent = i;
        btn.className = `mx-1 px-3 py-1 rounded ${i === currentPage ? 'bg-yellow-400 text-white font-bold' : 'bg-yellow-100 text-yellow-900 hover:bg-yellow-300'}`;
        btn.onclick = () => {
            currentPage = i;
            renderTable(currentPage);
            renderPagination();
        };
        pagination.appendChild(btn);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    renderTable(currentPage);
    renderPagination();
});
</script>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?> 