<?php
$currentWeek = date('W');
$currentYear = date('Y');
$menu = new Menu();
$currentWeekMenu = $menu->getCurrentWeekMenu();
$weekInfo = $menu->getCurrentWeekInfo();
?>

<!-- Required Libraries -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container py-4">
    <div class="row g-4 mb-4">
        <!-- Card: Page Views (Gauge, Large, Left) -->
        <div class="col-md-8">
            <div class="card shadow-sm" style="min-height:220px;">
                <div class="card-body d-flex flex-column justify-content-center align-items-center p-4 pb-2">
                    <h6 class="card-title mb-2 fs-2 fw-bold"><i class="fas fa-eye text-info me-2"></i>Page Views</h6>
                    <div class="display-3 fw-bold text-primary mb-2" id="pageViewsNumber">1,234,567</div>
                    <canvas id="pageViewsChart" height="300"></canvas>
                </div>
            </div>
        </div>
        <!-- Stack the other cards vertically in col-md-4 -->
        <div class="col-md-4 d-flex flex-column gap-3">
            <div class="card shadow-sm flex-fill">
                <div class="card-body d-flex flex-column justify-content-center align-items-center p-3">
                    <h6 class="card-title mb-2 fs-6"><i class="fas fa-chart-bar text-secondary me-2"></i>Sessions</h6>
                    <canvas id="sessionsChart" height="50"></canvas>
                </div>
            </div>
            <div class="card shadow-sm flex-fill">
                <div class="card-body d-flex flex-column justify-content-center align-items-center p-3">
                    <h6 class="card-title mb-2 fs-6"><i class="fas fa-user-plus text-success me-2"></i>New Users</h6>
                    <div class="display-5 fw-bold text-dark mb-1" id="newUsersNumber">342,453</div>
                    <div class="text-success fw-semibold fs-6">+34.5%</div>
                </div>
            </div>
            <div class="card shadow-sm flex-fill">
                <div class="card-body d-flex flex-column justify-content-center align-items-center p-3">
                    <h6 class="card-title mb-2 fs-6"><i class="fas fa-mobile-alt text-primary me-2"></i>Device Sessions</h6>
                    <canvas id="deviceSessionsChart" height="50"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-4 mb-4">
        <!-- Card: Tổng số bài viết -->
        <div class="col-md-3">
            <div class="card shadow-sm" style="background: #f5f6fa; color: #222;">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-newspaper fa-2x me-3 text-secondary"></i>
                        <div>
                            <div class="fs-3 fw-bold"><?php echo $totalPosts; ?></div>
                            <div class="small">Bài viết</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Card: Tổng số học sinh -->
        <div class="col-md-3">
            <div class="card shadow-sm" style="background: #e8f5e9; color: #222;">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-graduation-cap fa-2x me-3 text-success"></i>
                        <div>
                            <div class="fs-3 fw-bold"><?php echo $totalStudents; ?></div>
                            <div class="small">Học sinh</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Card: Đăng ký mới -->
        <div class="col-md-3">
            <div class="card shadow-sm" style="background: #ede7f6; color: #222;">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-clipboard-list fa-2x me-3 text-primary"></i>
                        <div>
                            <div class="fs-3 fw-bold"><?php echo $totalRegistrations; ?></div>
                            <div class="small">Đăng ký mới</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Card: Thực đơn -->
        <div class="col-md-3">
            <div class="card shadow-sm" style="background: #fff3e0; color: #222;">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-utensils fa-2x me-3 text-warning"></i>
                        <div>
                            <div class="fs-3 fw-bold"><?php echo $totalMenus; ?></div>
                            <div class="small">Thực đơn</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Weekly Menu & Recent Activities (keep original, but below) -->
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header bg-light fw-bold">Thực đơn tuần này</div>
                <div class="card-body">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr>
                                <th>Thứ</th>
                                <th>Bữa sáng</th>
                                <th>Bữa trưa</th>
                                <th>Bữa xế</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
                            $dayNames = ['Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6'];
                            foreach ($days as $index => $day):
                                if (isset($currentWeekMenu[$day])):
                            ?>
                            <tr>
                                <td class="fw-bold"><?php echo $dayNames[$index]; ?></td>
                                <td><?php echo htmlspecialchars($currentWeekMenu[$day]['breakfast']); ?></td>
                                <td><?php echo htmlspecialchars($currentWeekMenu[$day]['lunch']); ?></td>
                                <td><?php echo htmlspecialchars($currentWeekMenu[$day]['snack']); ?></td>
                            </tr>
                            <?php
                                endif;
                            endforeach;
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header bg-light fw-bold">Hoạt động gần đây</div>
                <div class="card-body">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr>
                                <th>Thời gian</th>
                                <th>Hoạt động</th>
                                <th>Chi tiết</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>10:30 - 20/03/2024</td>
                                <td>Đăng ký mới</td>
                                <td>Nguyễn Văn A - Lớp 1</td>
                                <td><span class="badge bg-success">Hoàn thành</span></td>
                            </tr>
                            <tr>
                                <td>09:15 - 20/03/2024</td>
                                <td>Cập nhật thực đơn</td>
                                <td>Tuần 12 - 2024</td>
                                <td><span class="badge bg-info">Đã cập nhật</span></td>
                            </tr>
                            <tr>
                                <td>14:20 - 19/03/2024</td>
                                <td>Đăng bài viết</td>
                                <td>Thông báo lễ hội mùa xuân</td>
                                <td><span class="badge bg-primary">Đã đăng</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Chart.js - Sessions (Bar)
new Chart(document.getElementById('sessionsChart'), {
    type: 'bar',
    data: {
        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'],
        datasets: [{
            label: 'Sessions',
            data: [12, 19, 14, 17, 20],
            backgroundColor: '#4e73df',
            borderRadius: 6,
        }]
    },
    options: {
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, grid: { display: false } }, x: { grid: { display: false } } },
        responsive: true,
        maintainAspectRatio: false
    }
});
// Chart.js - Page Views (Doughnut as Gauge)
new Chart(document.getElementById('pageViewsChart'), {
    type: 'doughnut',
    data: {
        labels: ['Direct', 'Referral', 'Social', 'Organic'],
        datasets: [{
            data: [30, 20, 15, 35],
            backgroundColor: ['#f6c23e', '#36b9cc', '#1cc88a', '#4e73df'],
            borderWidth: 0
        }]
    },
    options: {
        cutout: '70%',
        plugins: { legend: { display: false } },
        responsive: true,
        maintainAspectRatio: false
    }
});
// Chart.js - Device Sessions (Horizontal Bar)
new Chart(document.getElementById('deviceSessionsChart'), {
    type: 'bar',
    data: {
        labels: ['Mobile', 'Tablet', 'Desktop'],
        datasets: [{
            label: 'Sessions',
            data: [60, 25, 15],
            backgroundColor: ['#36b9cc', '#1cc88a', '#858796'],
            borderRadius: 6,
        }]
    },
    options: {
        indexAxis: 'y',
        plugins: { legend: { display: false } },
        scales: { x: { beginAtZero: true, grid: { display: false } }, y: { grid: { display: false } } },
        responsive: true,
        maintainAspectRatio: false
    }
});
</script>

<style>
body {
    background: #f8f9fc;
}
.card {
    border: none;
    border-radius: 1rem;
}
.card .card-title {
    font-size: 1rem;
    font-weight: 600;
    color: #343a40;
}
.display-3 {
    font-size: 3.5rem;
}
.display-5 {
    font-size: 2.8rem;
}
.display-6 {
    font-size: 2.5rem;
}
.bg-light {
    background: #f8f9fc !important;
}
.table th {
    background: #f8f9fc;
}
.card-body canvas {
    max-height: 320px !important;
}
@media (max-width: 991.98px) {
    .col-md-8, .col-md-4 { flex: 0 0 100%; max-width: 100%; }
    .gap-3 > * { margin-bottom: 1rem !important; }
}
</style>
