<?php
$menus = $menuController->getAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Manage Menus</h2>
    <a href="index.php?action=menus&op=new" class="btn btn-primary">Add New Menu Item</a>
</div>

<?php if (isset($_GET['op']) && $_GET['op'] === 'new'): ?>
    <div class="card mb-4">
        <div class="card-header">
            <h3>New Menu Item</h3>
        </div>
        <div class="card-body">
            <form action="index.php?action=menus&op=create" method="POST">
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>
                <div class="mb-3">
                    <label for="url" class="form-label">URL</label>
                    <input type="text" class="form-control" id="url" name="url" required>
                </div>
                <div class="mb-3">
                    <label for="order" class="form-label">Order</label>
                    <input type="number" class="form-control" id="order" name="order" required>
                </div>
                <button type="submit" class="btn btn-primary">Create Menu Item</button>
                <a href="index.php?action=menus" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
<?php endif; ?>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>URL</th>
                <th>Order</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($menus as $menu): ?>
            <tr>
                <td><?php echo htmlspecialchars($menu['id']); ?></td>
                <td><?php echo htmlspecialchars($menu['title']); ?></td>
                <td><?php echo htmlspecialchars($menu['url']); ?></td>
                <td><?php echo htmlspecialchars($menu['order']); ?></td>
                <td>
                    <a href="index.php?action=menus&op=edit&id=<?php echo $menu['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                    <form action="index.php?action=menus&op=delete" method="POST" class="d-inline">
                        <input type="hidden" name="id" value="<?php echo $menu['id']; ?>">
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
