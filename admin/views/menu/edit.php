<?php ob_start(); ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0">Edit Menu</h2>
        <a href="index.php?page=menus" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="index.php?page=menus&action=update&id=<?php echo $menu['id']; ?>" method="POST">
                <div class="mb-3">
                    <label for="week_label" class="form-label">Week Label</label>
                    <input type="text" class="form-control" id="week_label" name="week_label" value="<?php echo htmlspecialchars($menu['week_label']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="monday" class="form-label">Monday</label>
                    <input type="text" class="form-control" id="monday" name="monday" value="<?php echo htmlspecialchars($menu['monday']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="tuesday" class="form-label">Tuesday</label>
                    <input type="text" class="form-control" id="tuesday" name="tuesday" value="<?php echo htmlspecialchars($menu['tuesday']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="wednesday" class="form-label">Wednesday</label>
                    <input type="text" class="form-control" id="wednesday" name="wednesday" value="<?php echo htmlspecialchars($menu['wednesday']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="thursday" class="form-label">Thursday</label>
                    <input type="text" class="form-control" id="thursday" name="thursday" value="<?php echo htmlspecialchars($menu['thursday']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="friday" class="form-label">Friday</label>
                    <input type="text" class="form-control" id="friday" name="friday" value="<?php echo htmlspecialchars($menu['friday']); ?>" required>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Menu
                </button>
            </form>
        </div>
    </div>
</div>
