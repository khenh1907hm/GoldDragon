<?php ob_start(); ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0">Add New Menu</h2>
        <a href="index.php?page=menus" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="index.php?page=menus&action=store" method="POST">
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

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Menu
                </button>
            </form>
        </div>
    </div>
</div>
