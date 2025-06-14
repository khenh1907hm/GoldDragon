<?php
$pageTitle = 'Chỉnh sửa thực đơn';
require_once __DIR__ . '/../layouts/header.php';

$menu = $menuController->getById($_GET['id']);
if (!$menu) {
    $_SESSION['error'] = "Không tìm thấy thực đơn";
    header('Location: index.php?page=menus');
    exit();
}

$monday = explode('|', $menu['monday']);
$tuesday = explode('|', $menu['tuesday']);
$wednesday = explode('|', $menu['wednesday']);
$thursday = explode('|', $menu['thursday']);
$friday = explode('|', $menu['friday']);
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Chỉnh sửa thực đơn</h3>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger">
                            <?php 
                            echo $_SESSION['error'];
                            unset($_SESSION['error']);
                            ?>
                        </div>
                    <?php endif; ?>

                    <form action="index.php?page=menus&action=update" method="POST">
                        <input type="hidden" name="id" value="<?php echo $menu['id']; ?>">
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="week_range">Chọn tuần</label>
                                    <input type="text" name="week_range" id="week_range" class="form-control" 
                                           value="<?php echo date('d/m/Y', strtotime($menu['start_date'])) . ' - ' . date('d/m/Y', strtotime($menu['end_date'])); ?>" required>
                                    <input type="hidden" name="start_date" id="start_date" value="<?php echo $menu['start_date']; ?>">
                                    <input type="hidden" name="end_date" id="end_date" value="<?php echo $menu['end_date']; ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12">
                                <h4>Thứ 2</h4>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Bữa sáng</label>
                                            <input type="text" name="monday_breakfast" class="form-control" value="<?php echo $monday[0]; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Bữa trưa</label>
                                            <input type="text" name="monday_lunch" class="form-control" value="<?php echo $monday[1]; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Bữa xế</label>
                                            <input type="text" name="monday_snack" class="form-control" value="<?php echo $monday[2]; ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12">
                                <h4>Thứ 3</h4>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Bữa sáng</label>
                                            <input type="text" name="tuesday_breakfast" class="form-control" value="<?php echo $tuesday[0]; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Bữa trưa</label>
                                            <input type="text" name="tuesday_lunch" class="form-control" value="<?php echo $tuesday[1]; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Bữa xế</label>
                                            <input type="text" name="tuesday_snack" class="form-control" value="<?php echo $tuesday[2]; ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12">
                                <h4>Thứ 4</h4>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Bữa sáng</label>
                                            <input type="text" name="wednesday_breakfast" class="form-control" value="<?php echo $wednesday[0]; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Bữa trưa</label>
                                            <input type="text" name="wednesday_lunch" class="form-control" value="<?php echo $wednesday[1]; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Bữa xế</label>
                                            <input type="text" name="wednesday_snack" class="form-control" value="<?php echo $wednesday[2]; ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12">
                                <h4>Thứ 5</h4>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Bữa sáng</label>
                                            <input type="text" name="thursday_breakfast" class="form-control" value="<?php echo $thursday[0]; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Bữa trưa</label>
                                            <input type="text" name="thursday_lunch" class="form-control" value="<?php echo $thursday[1]; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Bữa xế</label>
                                            <input type="text" name="thursday_snack" class="form-control" value="<?php echo $thursday[2]; ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12">
                                <h4>Thứ 6</h4>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Bữa sáng</label>
                                            <input type="text" name="friday_breakfast" class="form-control" value="<?php echo $friday[0]; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Bữa trưa</label>
                                            <input type="text" name="friday_lunch" class="form-control" value="<?php echo $friday[1]; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Bữa xế</label>
                                            <input type="text" name="friday_snack" class="form-control" value="<?php echo $friday[2]; ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">Cập nhật thực đơn</button>
                                <a href="index.php?page=menus" class="btn btn-secondary">Quay lại</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Thêm thư viện daterangepicker -->
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script>
$(document).ready(function() {
    $('#week_range').daterangepicker({
        startDate: moment('<?php echo $menu['start_date']; ?>'),
        endDate: moment('<?php echo $menu['end_date']; ?>'),
        minDate: moment().subtract(1, 'year'),
        maxDate: moment().add(1, 'year'),
        showWeekNumbers: true,
        locale: {
            format: 'DD/MM/YYYY',
            applyLabel: 'Chọn',
            cancelLabel: 'Hủy',
            fromLabel: 'Từ',
            toLabel: 'Đến',
            customRangeLabel: 'Tùy chọn',
            weekLabel: 'W',
            daysOfWeek: ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'],
            monthNames: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
            firstDay: 1
        },
        ranges: {
            'Tuần này': [moment().startOf('week'), moment().endOf('week')],
            'Tuần sau': [moment().add(1, 'week').startOf('week'), moment().add(1, 'week').endOf('week')],
            'Tuần sau nữa': [moment().add(2, 'week').startOf('week'), moment().add(2, 'week').endOf('week')]
        }
    });

    $('#week_range').on('apply.daterangepicker', function(ev, picker) {
        var startDate = picker.startDate;
        var endDate = picker.endDate;
        
        // Cập nhật giá trị cho các input ẩn
        $('#start_date').val(startDate.format('YYYY-MM-DD'));
        $('#end_date').val(endDate.format('YYYY-MM-DD'));
        
        // Cập nhật hiển thị
        $(this).val(startDate.format('DD/MM/YYYY') + ' - ' + endDate.format('DD/MM/YYYY'));
    });
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?> 