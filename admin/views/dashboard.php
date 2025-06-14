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

<div class="dashboard-container">
    <!-- Header -->
    <div class="dashboard-header">
        <h2>Bảng điều khiển</h2>
        <div class="date-info">
            <i class="fas fa-calendar-alt"></i>
            <?php echo date('d/m/Y'); ?>
        </div>
    </div>

    <!-- Main Content -->
    <div class="dashboard-content">
        <!-- Statistics Table -->
        <div class="table-section">
            <div class="section-header">
                <h3>Thống kê</h3>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Chỉ số</th>
                            <th>Giá trị</th>
                            <th>Xu hướng</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <i class="fas fa-newspaper text-primary"></i>
                                Tổng số bài viết
                            </td>
                            <td class="fw-bold"><?php echo $totalPosts; ?></td>
                            <td class="text-success">
                                <i class="fas fa-arrow-up"></i> 12%
                            </td>
                            <td>
                                <a href="index.php?page=posts" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i> Xem
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <i class="fas fa-graduation-cap text-success"></i>
                                Học sinh
                            </td>
                            <td class="fw-bold"><?php echo $totalStudents; ?></td>
                            <td class="text-success">
                                <i class="fas fa-arrow-up"></i> 8%
                            </td>
                            <td>
                                <a href="index.php?page=students" class="btn btn-sm btn-outline-success">
                                    <i class="fas fa-eye"></i> Xem
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <i class="fas fa-clipboard-list text-info"></i>
                                Đăng ký mới
                            </td>
                            <td class="fw-bold"><?php echo $totalRegistrations; ?></td>
                            <td class="text-success">
                                <i class="fas fa-arrow-up"></i> 15%
                            </td>
                            <td>
                                <a href="index.php?page=registrations" class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-eye"></i> Xem
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <i class="fas fa-utensils text-warning"></i>
                                Thực đơn
                            </td>
                            <td class="fw-bold"><?php echo $totalMenus; ?></td>
                            <td class="text-muted">
                                <i class="fas fa-sync"></i> Cập nhật
                            </td>
                            <td>
                                <a href="index.php?page=menus" class="btn btn-sm btn-outline-warning">
                                    <i class="fas fa-eye"></i> Xem
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Menu Table -->
        <div class="table-section">
            <div class="section-header">
                <h3>Thực đơn tuần này</h3>
                <a href="index.php?page=menus" class="btn btn-primary btn-sm">
                    <i class="fas fa-edit"></i> Quản lý thực đơn
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered">
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

        <!-- Recent Activities -->
        <div class="table-section">
            <div class="section-header">
                <h3>Hoạt động gần đây</h3>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered">
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

<style>
.dashboard-container {
    padding: 1rem;
}

.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #e3e6f0;
}

.dashboard-header h2 {
    margin: 0;
    font-size: 1.5rem;
    color: #2e59d9;
}

.date-info {
    color: #6c757d;
    font-size: 0.9rem;
}

.date-info i {
    margin-right: 0.5rem;
}

.table-section {
    background: white;
    border-radius: 0.5rem;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    margin-bottom: 1.5rem;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    border-bottom: 1px solid #e3e6f0;
}

.section-header h3 {
    margin: 0;
    font-size: 1.1rem;
    color: #2e59d9;
}

.table {
    margin-bottom: 0;
}

.table th {
    background-color: #f8f9fc;
    font-weight: 600;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 0.75rem;
}

.table td {
    padding: 0.75rem;
    vertical-align: middle;
}

.table td i {
    margin-right: 0.5rem;
    width: 1.25rem;
    text-align: center;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

.badge {
    padding: 0.5em 0.75em;
    font-weight: 500;
}

@media (max-width: 768px) {
    .dashboard-header {
        flex-direction: column;
        text-align: center;
    }

    .date-info {
        margin-top: 0.5rem;
    }

    .section-header {
        flex-direction: column;
        gap: 0.5rem;
        text-align: center;
    }

    .table td, .table th {
        padding: 0.5rem;
    }
}
</style>
