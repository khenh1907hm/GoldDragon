<?php ob_start(); ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0">Menu Management</h2>
        <a href="index.php?page=menus&action=create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Menu
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
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Week</th>
                            <th>Monday</th>
                            <th>Tuesday</th>
                            <th>Wednesday</th>
                            <th>Thursday</th>
                            <th>Friday</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($menus as $menu): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($menu['id']); ?></td>
                                <td><?php echo htmlspecialchars($menu['week_label']); ?></td>
                                <td><?php echo htmlspecialchars($menu['monday']); ?></td>
                                <td><?php echo htmlspecialchars($menu['tuesday']); ?></td>
                                <td><?php echo htmlspecialchars($menu['wednesday']); ?></td>
                                <td><?php echo htmlspecialchars($menu['thursday']); ?></td>
                                <td><?php echo htmlspecialchars($menu['friday']); ?></td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="index.php?page=menus&action=edit&id=<?php echo $menu['id']; ?>" 
                                           class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                                onclick="confirmDelete(<?php echo $menu['id']; ?>)" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
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
