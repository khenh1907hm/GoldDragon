<?php
$menu = new Menu();
$menus = $menu->getAll();

// Debug log
error_log("Menus in view: " . print_r($menus, true));

// In trực tiếp cấu trúc mảng $menus ra đầu trang để kiểm tra
if (true) {
    echo '<pre style="background:#fffbe6; color:#222; border:1px solid #ccc; padding:10px;">';
    print_r($menus);
    echo '</pre>';
}

// DEBUG: Log ra console trình duyệt
echo '<script>console.log("menus from PHP:", ' . json_encode($menus, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . ');</script>';

// DEBUG: Hiển thị trực tiếp ra màn hình để kiểm tra dữ liệu
if (isset($_GET['debug'])) {
    echo '<pre>$menus = ' . print_r($menus, true) . '</pre>';
}
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0">Quản lý thực đơn</h2>
        <a href="index.php?page=menus&action=create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Thêm thực đơn
        </a>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php 
                echo $_SESSION['success'];
                unset($_SESSION['success']);
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card dashboard-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th rowspan="2">Tuần</th>
                            <th colspan="3">Thứ 2</th>
                            <th colspan="3">Thứ 3</th>
                            <th colspan="3">Thứ 4</th>
                            <th colspan="3">Thứ 5</th>
                            <th colspan="3">Thứ 6</th>
                            <th rowspan="2">Thao tác</th>
                        </tr>
                        <tr>
                            <th>Sáng</th><th>Trưa</th><th>Xế</th>
                            <th>Sáng</th><th>Trưa</th><th>Xế</th>
                            <th>Sáng</th><th>Trưa</th><th>Xế</th>
                            <th>Sáng</th><th>Trưa</th><th>Xế</th>
                            <th>Sáng</th><th>Trưa</th><th>Xế</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($menus)): ?>
                            <tr>
                                <td colspan="16" class="text-center">Chưa có dữ liệu thực đơn</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($menus as $menu): ?>
                            <tr>
                                <td>
                                    <?php
                                    $startDate = new DateTime($menu['start_date']);
                                    $endDate = new DateTime($menu['end_date']);
                                    $weekNumber = (int)$startDate->format('W');
                                    echo "Tuần $weekNumber (" . $startDate->format('d/m/Y') . " - " . $endDate->format('d/m/Y') . ")";
                                    ?>
                                </td>
                                <td><?php echo htmlspecialchars($menu['monday_breakfast']); ?></td>
                                <td><?php echo htmlspecialchars($menu['monday_lunch']); ?></td>
                                <td><?php echo htmlspecialchars($menu['monday_snack']); ?></td>
                                <td><?php echo htmlspecialchars($menu['tuesday_breakfast']); ?></td>
                                <td><?php echo htmlspecialchars($menu['tuesday_lunch']); ?></td>
                                <td><?php echo htmlspecialchars($menu['tuesday_snack']); ?></td>
                                <td><?php echo htmlspecialchars($menu['wednesday_breakfast']); ?></td>
                                <td><?php echo htmlspecialchars($menu['wednesday_lunch']); ?></td>
                                <td><?php echo htmlspecialchars($menu['wednesday_snack']); ?></td>
                                <td><?php echo htmlspecialchars($menu['thursday_breakfast']); ?></td>
                                <td><?php echo htmlspecialchars($menu['thursday_lunch']); ?></td>
                                <td><?php echo htmlspecialchars($menu['thursday_snack']); ?></td>
                                <td><?php echo htmlspecialchars($menu['friday_breakfast']); ?></td>
                                <td><?php echo htmlspecialchars($menu['friday_lunch']); ?></td>
                                <td><?php echo htmlspecialchars($menu['friday_snack']); ?></td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="index.php?page=menus&action=edit&id=<?php echo $menu['id']; ?>" 
                                           class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="index.php?page=menus&action=delete" method="POST" class="d-inline">
                                            <input type="hidden" name="id" value="<?php echo $menu['id']; ?>">
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</button>
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
    </div>
</div>

<script>
function confirmDelete(id) {
    if (confirm('Are you sure you want to delete this menu?')) {
        window.location.href = 'index.php?page=menus&action=delete&id=' + id;
    }
}
</script>
