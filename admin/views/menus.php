<?php
$menu = new Menu();
$menus = $menu->getAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Manage Menus</h2>
    <a href="index.php?page=menus&op=new" class="btn btn-primary">Add New Menu</a>
</div>

<?php if (isset($_GET['op']) && $_GET['op'] === 'new'): ?>
    <div class="card mb-4">
        <div class="card-header">
            <h3>New Menu</h3>
        </div>
        <div class="card-body">
            <form action="index.php?page=menus&op=create" method="POST">
                <div class="mb-3">
                    <label for="week_label" class="form-label">Week Label</label>
                    <input type="text" class="form-control" id="week_label" name="week_label" required>
                </div>
                <div class="mb-3">
                    <label for="monday" class="form-label">Monday</label>
                    <input type="text" class="form-control" id="monday" name="monday" required>
                </div>
                <div class="mb-3">
                    <label for="tuesday" class="form-label">Tuesday</label>
                    <input type="text" class="form-control" id="tuesday" name="tuesday" required>
                </div>
                <div class="mb-3">
                    <label for="wednesday" class="form-label">Wednesday</label>
                    <input type="text" class="form-control" id="wednesday" name="wednesday" required>
                </div>
                <div class="mb-3">
                    <label for="thursday" class="form-label">Thursday</label>
                    <input type="text" class="form-control" id="thursday" name="thursday" required>
                </div>
                <div class="mb-3">
                    <label for="friday" class="form-label">Friday</label>
                    <input type="text" class="form-control" id="friday" name="friday" required>
                </div>
                <button type="submit" class="btn btn-primary">Create Menu</button>
                <a href="index.php?page=menus" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
<?php endif; ?>

<div class="table-responsive">
    <table class="table table-striped">
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
                    <a href="index.php?page=menus&op=edit&id=<?php echo $menu['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                    <form action="index.php?page=menus&op=delete" method="POST" class="d-inline">
                        <input type="hidden" name="id" value="<?php echo $menu['id']; ?>">
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
