<?php
$menu = new Menu();
$menus = $menu->getAll();

// Debug log
error_log("Menus in view: " . print_r($menus, true));

// If we have a new menu in session, add it to the list
if (isset($_SESSION['new_menu'])) {
    $newMenu = $_SESSION['new_menu'];
    // Debug log
    error_log("New menu from session: " . print_r($newMenu, true));
    
    // Format the new menu
    $formattedNewMenu = [
        'id' => $newMenu['id'],
        'week_label' => 'Tuần ' . date('W', strtotime($newMenu['start_date'])) . 
                       ' (' . date('d/m/Y', strtotime($newMenu['start_date'])) . ' - ' . 
                       date('d/m/Y', strtotime($newMenu['end_date'])) . ')',
        'start_date' => $newMenu['start_date'],
        'end_date' => $newMenu['end_date'],
        'monday' => [
            'breakfast' => $newMenu['monday_breakfast'] ?? '',
            'lunch' => $newMenu['monday_lunch'] ?? '',
            'snack' => $newMenu['monday_snack'] ?? ''
        ],
        'tuesday' => [
            'breakfast' => $newMenu['tuesday_breakfast'] ?? '',
            'lunch' => $newMenu['tuesday_lunch'] ?? '',
            'snack' => $newMenu['tuesday_snack'] ?? ''
        ],
        'wednesday' => [
            'breakfast' => $newMenu['wednesday_breakfast'] ?? '',
            'lunch' => $newMenu['wednesday_lunch'] ?? '',
            'snack' => $newMenu['wednesday_snack'] ?? ''
        ],
        'thursday' => [
            'breakfast' => $newMenu['thursday_breakfast'] ?? '',
            'lunch' => $newMenu['thursday_lunch'] ?? '',
            'snack' => $newMenu['thursday_snack'] ?? ''
        ],
        'friday' => [
            'breakfast' => $newMenu['friday_breakfast'] ?? '',
            'lunch' => $newMenu['friday_lunch'] ?? '',
            'snack' => $newMenu['friday_snack'] ?? ''
        ]
    ];
    
    // Debug log
    error_log("Formatted new menu: " . print_r($formattedNewMenu, true));
    
    // Add to the beginning of the menus array
    array_unshift($menus, $formattedNewMenu);
    // Clear the session
    unset($_SESSION['new_menu']);
}

$currentDate = date('Y-m-d');

// Get current month info
$currentMonth = date('m');
$currentYear = date('Y');
$currentWeek = date('W');

// Generate weeks for current month
$weeks = [];
$firstDayOfMonth = new DateTime("$currentYear-$currentMonth-01");
$lastDayOfMonth = new DateTime("$currentYear-$currentMonth-" . $firstDayOfMonth->format('t'));

// Get first week of month
$firstWeek = (int)$firstDayOfMonth->format('W');
// Get last week of month
$lastWeek = (int)$lastDayOfMonth->format('W');

// If month spans across years, adjust weeks
if ($firstDayOfMonth->format('Y') != $lastDayOfMonth->format('Y')) {
    $lastWeek = 52 + (int)$lastDayOfMonth->format('W');
}

// Generate all weeks in the month
for ($week = $firstWeek; $week <= $lastWeek; $week++) {
    $dto = new DateTime();
    $dto->setISODate($currentYear, $week);
    $startDate = $dto->format('Y-m-d');
    $dto->modify('+4 days');
    $endDate = $dto->format('Y-m-d');
    
    // Only add if the week is in current month
    if ($dto->format('m') == $currentMonth) {
        $weeks[] = [
            'week' => $week,
            'year' => $currentYear,
            'label' => "Tuần $week (" . date('d/m/Y', strtotime($startDate)) . " - " . date('d/m/Y', strtotime($endDate)) . ")",
            'start_date' => $startDate,
            'end_date' => $endDate,
            'is_current' => ($week == $currentWeek)
        ];
    }
}

// Get current week menu
$currentWeekMenu = null;
if (!empty($weeks)) {
    foreach ($weeks as $week) {
        if ($week['is_current']) {
            $currentWeekMenu = $menu->getByDateRange($week['start_date'], $week['end_date']);
            break;
        }
    }
}

// Format menus for display
$formattedMenus = [];
foreach ($menus as $menu) {
    if (!empty($menu['start_date']) && !empty($menu['end_date'])) {
        $startDate = new DateTime($menu['start_date']);
        $endDate = new DateTime($menu['end_date']);
        $weekNumber = (int)$startDate->format('W');
        
        $formattedMenu = [
            'id' => $menu['id'],
            'week_label' => "Tuần $weekNumber (" . $startDate->format('d/m/Y') . " - " . $endDate->format('d/m/Y') . ")",
            'start_date' => $menu['start_date'],
            'end_date' => $menu['end_date'],
            'monday' => [
                'breakfast' => $menu['monday_breakfast'] ?? '',
                'lunch' => $menu['monday_lunch'] ?? '',
                'snack' => $menu['monday_snack'] ?? ''
            ],
            'tuesday' => [
                'breakfast' => $menu['tuesday_breakfast'] ?? '',
                'lunch' => $menu['tuesday_lunch'] ?? '',
                'snack' => $menu['tuesday_snack'] ?? ''
            ],
            'wednesday' => [
                'breakfast' => $menu['wednesday_breakfast'] ?? '',
                'lunch' => $menu['wednesday_lunch'] ?? '',
                'snack' => $menu['wednesday_snack'] ?? ''
            ],
            'thursday' => [
                'breakfast' => $menu['thursday_breakfast'] ?? '',
                'lunch' => $menu['thursday_lunch'] ?? '',
                'snack' => $menu['thursday_snack'] ?? ''
            ],
            'friday' => [
                'breakfast' => $menu['friday_breakfast'] ?? '',
                'lunch' => $menu['friday_lunch'] ?? '',
                'snack' => $menu['friday_snack'] ?? ''
            ]
        ];
        
        // Debug log each formatted menu
        error_log("Formatted menu: " . print_r($formattedMenu, true));
        
        $formattedMenus[] = $formattedMenu;
    }
}

// Debug log final formatted menus
error_log("Final formatted menus in view: " . print_r($formattedMenus, true));
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Quản lý thực đơn</h3>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success">
                            <?php 
                            echo $_SESSION['success'];
                            unset($_SESSION['success']);
                            ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger">
                            <?php 
                            echo $_SESSION['error'];
                            unset($_SESSION['error']);
                            ?>
                        </div>
                    <?php endif; ?>

                    <!-- Form thêm thực đơn -->
                    <form action="index.php?page=menus&action=create" method="POST" class="mb-4" id="menuForm">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="week_select">Chọn tuần</label>
                                    <select name="week_select" id="week_select" class="form-control" required>
                                        <option value="">-- Chọn tuần --</option>
                                        <?php foreach ($weeks as $week): ?>
                                            <option value="<?php echo $week['start_date'] . '|' . $week['end_date']; ?>"
                                                    data-start="<?php echo $week['start_date']; ?>"
                                                    data-end="<?php echo $week['end_date']; ?>"
                                                    <?php echo $week['is_current'] ? 'selected' : ''; ?>>
                                                <?php echo $week['label']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="hidden" name="start_date" id="start_date" value="<?php echo $weeks[0]['start_date']; ?>">
                                    <input type="hidden" name="end_date" id="end_date" value="<?php echo $weeks[0]['end_date']; ?>">
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
                                            <input type="text" name="monday_breakfast" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Bữa trưa</label>
                                            <input type="text" name="monday_lunch" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Bữa xế</label>
                                            <input type="text" name="monday_snack" class="form-control">
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
                                            <input type="text" name="tuesday_breakfast" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Bữa trưa</label>
                                            <input type="text" name="tuesday_lunch" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Bữa xế</label>
                                            <input type="text" name="tuesday_snack" class="form-control">
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
                                            <input type="text" name="wednesday_breakfast" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Bữa trưa</label>
                                            <input type="text" name="wednesday_lunch" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Bữa xế</label>
                                            <input type="text" name="wednesday_snack" class="form-control">
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
                                            <input type="text" name="thursday_breakfast" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Bữa trưa</label>
                                            <input type="text" name="thursday_lunch" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Bữa xế</label>
                                            <input type="text" name="thursday_snack" class="form-control">
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
                                            <input type="text" name="friday_breakfast" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Bữa trưa</label>
                                            <input type="text" name="friday_lunch" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Bữa xế</label>
                                            <input type="text" name="friday_snack" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary" id="submitBtn">Thêm thực đơn</button>
                                <button type="button" class="btn btn-success" id="updateBtn" style="display: none;">Cập nhật thực đơn</button>
                                <input type="hidden" name="menu_id" id="menu_id">
                            </div>
                        </div>
                    </form>

                    <!-- Danh sách thực đơn -->
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
                                    <th>Sáng</th>
                                    <th>Trưa</th>
                                    <th>Xế</th>
                                    <th>Sáng</th>
                                    <th>Trưa</th>
                                    <th>Xế</th>
                                    <th>Sáng</th>
                                    <th>Trưa</th>
                                    <th>Xế</th>
                                    <th>Sáng</th>
                                    <th>Trưa</th>
                                    <th>Xế</th>
                                    <th>Sáng</th>
                                    <th>Trưa</th>
                                    <th>Xế</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($formattedMenus)): ?>
                                    <tr>
                                        <td colspan="16" class="text-center">Chưa có dữ liệu thực đơn</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($formattedMenus as $menu): ?>
                                    <tr>
                                        <td><?php echo $menu['week_label']; ?></td>
                                        <td><?php echo htmlspecialchars($menu['monday']['breakfast']); ?></td>
                                        <td><?php echo htmlspecialchars($menu['monday']['lunch']); ?></td>
                                        <td><?php echo htmlspecialchars($menu['monday']['snack']); ?></td>
                                        <td><?php echo htmlspecialchars($menu['tuesday']['breakfast']); ?></td>
                                        <td><?php echo htmlspecialchars($menu['tuesday']['lunch']); ?></td>
                                        <td><?php echo htmlspecialchars($menu['tuesday']['snack']); ?></td>
                                        <td><?php echo htmlspecialchars($menu['wednesday']['breakfast']); ?></td>
                                        <td><?php echo htmlspecialchars($menu['wednesday']['lunch']); ?></td>
                                        <td><?php echo htmlspecialchars($menu['wednesday']['snack']); ?></td>
                                        <td><?php echo htmlspecialchars($menu['thursday']['breakfast']); ?></td>
                                        <td><?php echo htmlspecialchars($menu['thursday']['lunch']); ?></td>
                                        <td><?php echo htmlspecialchars($menu['thursday']['snack']); ?></td>
                                        <td><?php echo htmlspecialchars($menu['friday']['breakfast']); ?></td>
                                        <td><?php echo htmlspecialchars($menu['friday']['lunch']); ?></td>
                                        <td><?php echo htmlspecialchars($menu['friday']['snack']); ?></td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary edit-btn" 
                                                    data-id="<?php echo $menu['id']; ?>"
                                                    data-start="<?php echo $menu['start_date']; ?>"
                                                    data-end="<?php echo $menu['end_date']; ?>">
                                                Sửa
                                            </button>
                                            <form action="index.php?page=menus&action=delete" method="POST" class="d-inline">
                                                <input type="hidden" name="id" value="<?php echo $menu['id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</button>
                                            </form>
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
    </div>
</div>

<!-- Thêm thư viện daterangepicker -->
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script>
$(document).ready(function() {
    // Function to load menu data
    function loadMenuData(menu) {
        if (menu) {
            // Fill form with menu data
            $('input[name="monday_breakfast"]').val(menu.monday_breakfast || '');
            $('input[name="monday_lunch"]').val(menu.monday_lunch || '');
            $('input[name="monday_snack"]').val(menu.monday_snack || '');
            $('input[name="tuesday_breakfast"]').val(menu.tuesday_breakfast || '');
            $('input[name="tuesday_lunch"]').val(menu.tuesday_lunch || '');
            $('input[name="tuesday_snack"]').val(menu.tuesday_snack || '');
            $('input[name="wednesday_breakfast"]').val(menu.wednesday_breakfast || '');
            $('input[name="wednesday_lunch"]').val(menu.wednesday_lunch || '');
            $('input[name="wednesday_snack"]').val(menu.wednesday_snack || '');
            $('input[name="thursday_breakfast"]').val(menu.thursday_breakfast || '');
            $('input[name="thursday_lunch"]').val(menu.thursday_lunch || '');
            $('input[name="thursday_snack"]').val(menu.thursday_snack || '');
            $('input[name="friday_breakfast"]').val(menu.friday_breakfast || '');
            $('input[name="friday_lunch"]').val(menu.friday_lunch || '');
            $('input[name="friday_snack"]').val(menu.friday_snack || '');
            
            // Show update button and hide submit button
            $('#submitBtn').hide();
            $('#updateBtn').show();
            $('#menu_id').val(menu.id);
        } else {
            // Clear form
            $('input[type="text"]').val('');
            // Show submit button and hide update button
            $('#submitBtn').show();
            $('#updateBtn').hide();
            $('#menu_id').val('');
        }
    }

    // Handle week selection change
    $('#week_select').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        var startDate = selectedOption.data('start');
        var endDate = selectedOption.data('end');
        
        // Update hidden inputs
        $('#start_date').val(startDate);
        $('#end_date').val(endDate);
        
        // Check if menu exists for selected week
        $.ajax({
            url: 'index.php?page=menus&action=check',
            method: 'POST',
            data: {
                start_date: startDate,
                end_date: endDate
            },
            success: function(response) {
                if (response.exists) {
                    loadMenuData(response.menu);
                } else {
                    loadMenuData(null);
                }
            }
        });
    });

    // Handle edit button click
    $('.edit-btn').on('click', function() {
        var menuId = $(this).data('id');
        var startDate = $(this).data('start');
        var endDate = $(this).data('end');
        
        // Set the week select to the correct week
        $('#week_select').val(startDate + '|' + endDate).trigger('change');
        
        // Load menu data
        $.ajax({
            url: 'index.php?page=menus&action=get',
            method: 'POST',
            data: {
                id: menuId
            },
            success: function(response) {
                if (response.success) {
                    loadMenuData(response.menu);
                }
            }
        });
    });

    // Handle update button click
    $('#updateBtn').on('click', function() {
        var menuId = $('#menu_id').val();
        if (menuId) {
            $('#menuForm').attr('action', 'index.php?page=menus&action=update');
            $('#menuForm').submit();
        }
    });

    // Auto-load current week data on page load
    var currentWeekOption = $('#week_select option:selected');
    if (currentWeekOption.length) {
        currentWeekOption.trigger('change');
    }
});
</script>
